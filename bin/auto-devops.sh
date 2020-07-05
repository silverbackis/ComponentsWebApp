#!/usr/bin/env bash

# echo the commands that are run
[[ "$TRACE" ]] && set -x

export CI_APPLICATION_REPOSITORY=$CI_REGISTRY_IMAGE/$CI_COMMIT_REF_SLUG
export CI_APPLICATION_TAG=$CI_COMMIT_SHA

export GITLAB_PULL_SECRET_NAME=gitlab-registry
export KUBERNETES_VERSION=1.18.2
export HELM_VERSION=3.2.1

# Choose the branch for production deploy.
if [[ -z "$DEPLOYMENT_BRANCH" ]]; then
  export DEPLOYMENT_BRANCH=master
fi

# Miscellaneous
if [[ -z "$CLUSTER_ISSUER" ]]; then
  export CLUSTER_ISSUER="letsencrypt-staging"
fi
if [[ -z "$LETSENCRYPT_SECRET_NAME" ]]; then
  export LETSENCRYPT_SECRET_NAME="letsencrypt-cert"
fi

export DOMAIN=$(basename ${CI_ENVIRONMENT_URL})
export DOCKER_REPOSITORY=${CI_REGISTRY_IMAGE}
export PHP_REPOSITORY="${DOCKER_REPOSITORY}/php"
export NGINX_REPOSITORY="${DOCKER_REPOSITORY}/nginx"
export VARNISH_REPOSITORY="${DOCKER_REPOSITORY}/varnish"

export MERCURE_SUBSCRIBE_DOMAIN="${DOMAIN/api./mercure.}"

if [[ -z "$CORS_ALLOW_ORIGIN" ]]; then
  echo "!!!! WARNING CORS_ALLOW_ORIGIN ENVIRONMENT IS NOT SET !!!!";
  echo "Expected a regex string similar to ^https?:\/\/(.*\.)?example\.com"
fi
if [[ -z "$TRUSTED_HOSTS" ]]; then
  echo '!!!! WARNING TRUSTED_HOSTS ENVIRONMENT IS NOT SET !!!!';
  echo "Expected a regex string similar to ^.*\.example\.com$"
fi

if [[ "$CI_COMMIT_REF_NAME" == "$DEPLOYMENT_BRANCH" ]]; then
  export RELEASE="${CI_ENVIRONMENT_SLUG}"
  export TAG=${CI_COMMIT_REF_SLUG}
else
  if [[ -n "$CI_ENVIRONMENT_SLUG" ]] && [[ -z "$RELEASE" ]]; then
    export RELEASE="${CI_ENVIRONMENT_SLUG}"
  fi
  if [[ -z "$RELEASE" ]]; then echo 'Helm RELEASE environment variable is not defined in your ci environment variables for non-production helm releases.'; fi
  export TAG=${CI_COMMIT_REF_SLUG:-dev}
  echo "CONTAINER TAG: '${TAG}'"
fi

# To enable blackfire, set the environment variables
if [[ -n "$BLACKFIRE_SERVER_ID" ]] && [[ -n "$BLACKFIRE_SERVER_TOKEN" ]] ; then
  export BLACKFIRE_SERVER_ENABLED=true
fi

rand_str() {
  len=32
  head -c 256 /dev/urandom > /tmp/urandom.out
  tr -dc 'a-zA-Z0-9' < /tmp/urandom.out > /tmp/urandom.tr
  head -c ${len} /tmp/urandom.tr
}

install_dependencies() {
  echo "Adding openssl curl tar gzip ca-certificates git nodejs nodejs-npm"
  # upgrade for curl fix https://github.com/curl/curl/issues/4357
  apk add --update-cache --upgrade --no-cache -U openssl curl tar gzip ca-certificates git nodejs nodejs-npm

  wget -q -O /etc/apk/keys/sgerrand.rsa.pub https://alpine-pkgs.sgerrand.com/sgerrand.rsa.pub
  wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/2.28-r0/glibc-2.28-r0.apk
  apk add glibc-2.28-r0.apk
  rm glibc-2.28-r0.apk

  echo "Intalling helm..."
  curl "https://get.helm.sh/helm-v${HELM_VERSION}-linux-amd64.tar.gz" | tar zx
  mv linux-amd64/helm /usr/bin/

  helm version --client

  echo "Intalling kubectl..."
  curl -L -o /usr/bin/kubectl "https://storage.googleapis.com/kubernetes-release/release/v${KUBERNETES_VERSION}/bin/linux/amd64/kubectl"
  chmod +x /usr/bin/kubectl
  kubectl version --client

  # Generate random passphrase and keys for JWT signing if not set
	if [[ -z ${JWT_PASSPHRASE} ]]; then
		JWT_PASSPHRASE="$(rand_str)"
		export JWT_PASSPHRASE
	fi

	if [[ -z ${JWT_SECRET_KEY} ]]; then
		JWT_SECRET_KEY_FILE=/tmp/jwt_secret

		openssl genpkey -pass pass:"${JWT_PASSPHRASE}" -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -out ${JWT_SECRET_KEY_FILE}
		JWT_SECRET_KEY=$(cat ${JWT_SECRET_KEY_FILE})
		export JWT_SECRET_KEY

		JWT_PUBLIC_KEY=$(openssl pkey -in "$JWT_SECRET_KEY_FILE" -passin pass:"$JWT_PASSPHRASE" -pubout)
		export JWT_PUBLIC_KEY

		rm ${JWT_SECRET_KEY_FILE}
	fi

	echo "Checking/generating \$MERCURE_JWT_KEY"
  # Generate random key & jwt for Mercure if not set
  if [[ -z ${MERCURE_JWT_SECRET} ]]; then
    MERCURE_JWT_SECRET="$(rand_str)"
    export MERCURE_JWT_SECRET
  fi
  if [[ -z ${MERCURE_JWT_TOKEN} ]]; then
    npm install --global "@clarketm/jwt-cli"
    MERCURE_JWT_TOKEN=$(jwt sign --noCopy --expiresIn "100 years" '{"mercure": {"publish": ["*"]}}' "$MERCURE_JWT_SECRET")
    export MERCURE_JWT_TOKEN
  fi
}

# For Kubernetes environment gitlab runner use the localhost for DIND - see https://docs.gitlab.com/runner/executors/kubernetes.html#using-dockerdind
# Using shared runners for now.
setup_docker() {
  if ! docker info &>/dev/null; then
    if [[ -z "$DOCKER_HOST" && "$KUBERNETES_PORT" ]]; then
      export DOCKER_HOST='tcp://localhost:2375'
    fi
  fi
}

build() {
  # https://gitlab.com/help/ci/variables/predefined_variables.md
  if [[ -n "$CI_REGISTRY_USER" ]]; then
    echo "Logging to GitLab Container Registry with CI credentials..."
    docker login -u "$CI_REGISTRY_USER" -p "$CI_REGISTRY_PASSWORD" "$CI_REGISTRY"
    echo ""
  fi

  docker pull $PHP_REPOSITORY:$TAG || true
  docker build \
  	--cache-from $PHP_REPOSITORY:$TAG \
  	--tag $PHP_REPOSITORY:$TAG \
  	--target cwa_php \
  	"api"

  docker pull $NGINX_REPOSITORY:$TAG || true
  docker build \
  	--cache-from $PHP_REPOSITORY:$TAG \
  	--cache-from $NGINX_REPOSITORY:$TAG \
  	--tag $NGINX_REPOSITORY:$TAG \
  	--target cwa_nginx \
  	"api"

  docker pull $VARNISH_REPOSITORY:$TAG || true
  docker build \
  	--cache-from $PHP_REPOSITORY:$TAG \
  	--cache-from $VARNISH_REPOSITORY:$TAG \
  	--tag $VARNISH_REPOSITORY:$TAG \
  	--target cwa_varnish \
  	"api"

  docker push $PHP_REPOSITORY:$TAG
  docker push $NGINX_REPOSITORY:$TAG
  docker push $VARNISH_REPOSITORY:$TAG
}

function setup_test_db() {
  if [[ -z ${KUBERNETES_PORT+x} ]]; then
    DB_HOST=postgres
  else
    DB_HOST=localhost
  fi
  export DATABASE_URL="pgsql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@${DB_HOST}:5432/${POSTGRES_DB}"
}

function run_phpunit() {
  echo "run_phpunit function"
  cd ./api || return
  mkdir -p build/logs/phpunit/
  composer install -o --prefer-dist --no-scripts --ignore-platform-reqs
  vendor/bin/simple-phpunit tests/Unit --log-junit build/logs/phpunit/junit.xml
}

function run_behat() {
  echo "run_behat function"
  cd ./api || return
  mkdir -p build/logs/behat/
  composer install -o --prefer-dist --no-scripts --ignore-platform-reqs
  bin/console doctrine:query:sql "CREATE EXTENSION IF NOT EXISTS citext;"
  vendor/bin/behat --format=progress --out=std --format=junit --out=build/logs/behat/junit --profile=default --no-interaction --colors --tags='~@wip'
}

check_kube_domain() {
  if [[ -z ${CI_ENVIRONMENT_URL+x} ]]; then
    echo "In order to deploy or use Review Apps, CI_ENVIRONMENT_URL variable must be set"
    echo "You can do it in Auto DevOps project settings or defining a variable at group or project level"
    echo "You can also manually add it in .gitlab-ci.yml"
    false
  else
    true
  fi
}

helm_init() {
  rm -rf ~/.helm/repository/cache/*
  helm repo add default https://kubernetes-charts.storage.googleapis.com
  helm dependency update api/_helm/api
  helm dependency build api/_helm/api
}

ensure_namespace() {
  kubectl describe namespace "$KUBE_NAMESPACE" || kubectl create namespace "$KUBE_NAMESPACE"
}

create_secret() {
  if [[ "$CI_PROJECT_VISIBILITY" = "public" ]]; then
  	echo "Project is public - skipping secret creation"
    return
  fi
  echo "Create secret..."

  kubectl create secret -n "$KUBE_NAMESPACE" \
    docker-registry $GITLAB_PULL_SECRET_NAME \
    --docker-server="$CI_REGISTRY" \
    --docker-username="${CI_DEPLOY_USER:-$CI_REGISTRY_USER}" \
    --docker-password="${CI_DEPLOY_PASSWORD:-$CI_REGISTRY_PASSWORD}" \
    --docker-email="$GITLAB_USER_EMAIL" \
    -o yaml --dry-run | kubectl replace -n "$KUBE_NAMESPACE" --force -f -
}

function get_replicas() {
	track="${1:-stable}"
	percentage="${2:-100}"

	env_track=$( echo $track | tr -s  '[:lower:]'  '[:upper:]' )
	env_slug=$( echo ${CI_ENVIRONMENT_SLUG//-/_} | tr -s  '[:lower:]'  '[:upper:]' )

	if [[ "$track" == "stable" ]] || [[ "$track" == "rollout" ]]; then
		# for stable track get number of replicas from `PRODUCTION_REPLICAS`
		eval new_replicas=\$${env_slug}_REPLICAS
		if [[ -z "$new_replicas" ]]; then
			new_replicas=$REPLICAS
		fi
	else
		# for all tracks get number of replicas from `CANARY_PRODUCTION_REPLICAS`
		eval new_replicas=\$${env_track}_${env_slug}_REPLICAS
		if [[ -z "$new_replicas" ]]; then
			eval new_replicas=\$env_track_REPLICAS
		fi
	fi

	replicas="${new_replicas:-1}"
	replicas="$(($replicas * $percentage / 100))"

	# always return at least one replica
	if [[ $replicas -gt 0 ]]; then
		echo "$replicas"
	else
		echo 1
	fi
}

deploy_api() {
	track="${1-stable}"
	percentage="${2:-100}"
	name="$RELEASE"
	if [[ "$track" != "stable" ]]; then
		name="$name-$track"
	fi
	if [[ "$track" == "canary" ]]; then
		export BLACKFIRE_SERVER_ENABLED=false
	fi
	echo "Installing/upgrading release '${name}' on namespace '${KUBE_NAMESPACE}' and host '${DOMAIN}' (${CI_ENVIRONMENT_URL})"

	replicas=$(get_replicas "$track" "$percentage")

  if [[ -n "$HELM_UNINSTALL" ]]; then
    helm uninstall "$name" || EXIT_CODE=$? && true
    echo ${EXIT_CODE}
  fi

  cat >values.tmp.yaml <<EOF
imagePullSecrets:
  - name: ${GITLAB_PULL_SECRET_NAME:-"~"}
php:
  image:
    repository: ${PHP_REPOSITORY}
    tag: ${TAG}
  corsAllowOrigin: ${CORS_ALLOW_ORIGIN:-"~"}
  trustedHosts: ${TRUSTED_HOSTS:-"~"}
  mercure:
    jwtToken: ${MERCURE_JWT_TOKEN:-"~"}
    subscribeUrl: https://${MERCURE_SUBSCRIBE_DOMAIN}/.well-known/mercure
  databaseUrl: ${DATABASE_URL:-"~"}
  apiSecretToken: ${VARNISH_TOKEN:-"~"}
  mailer:
    dsn: ${MAILER_DSN:-"~"}
    email: ${MAILER_EMAIL:-"~"}
  jwt:
    passphrase: "${JWT_PASSPHRASE:-"~"}"
  blackfire:
    id: "${BLACKFIRE_CLIENT_ID}"
    token: "${BLACKFIRE_CLIENT_TOKEN}"
nginx:
  image:
    repository: ${NGINX_REPOSITORY}
    tag: ${TAG}
varnish:
  image:
    repository: ${VARNISH_REPOSITORY}
    tag: ${TAG}
ingress:
  enabled: ${INGRESS_ENABLED:-"false"}
  annotations:
    "kubernetes.io/ingress.class": nginx
    "certmanager.k8s.io/cluster-issuer": ${CLUSTER_ISSUER:-"~"}
  hosts:
    - host: ${DOMAIN:-"~"}
      paths:
        - /
  tls:
    - secretName: ${LETSENCRYPT_SECRET_NAME}-api
      hosts:
        - ${DOMAIN:-"~"}
mercure:
  jwtKey: ${MERCURE_JWT_SECRET:-"~"}
  ingress:
    enabled: ${INGRESS_ENABLED:-false}
    annotations:
      "kubernetes.io/ingress.class": nginx
      "certmanager.k8s.io/cluster-issuer": ${CLUSTER_ISSUER}
    hosts:
      - host: "${MERCURE_SUBSCRIBE_DOMAIN}"
        paths:
          - /
    tls:
      - secretName: ${LETSENCRYPT_SECRET_NAME}-mercure
        hosts:
          - "${MERCURE_SUBSCRIBE_DOMAIN}"
  corsAllowedOrigins:
    - ${CORS_ALLOW_ORIGIN:-"~"}
blackfire:
  enabled: ${BLACKFIRE_SERVER_ENABLED:-false}
  server:
    id: "${BLACKFIRE_SERVER_ID}"
    token: "${BLACKFIRE_SERVER_TOKEN}"
annotations:
  app.gitlab.com/app: "${CI_PROJECT_PATH_SLUG}"
  app.gitlab.com/env: "${CI_ENVIRONMENT_SLUG}"
EOF

  helm upgrade --install \
    --reset-values \
    --force \
    --namespace="$KUBE_NAMESPACE" \
    "$name" ./api/_helm/api \
    --set php.jwt.secret="${JWT_SECRET_KEY}" \
    --set php.jwt.public="${JWT_PUBLIC_KEY}" \
  	-f values.tmp.yaml
}

persist_environment_url() {
	echo $CI_ENVIRONMENT_URL > environment_url.txt
}

performance() {
  export CI_ENVIRONMENT_URL=$(cat environment_url.txt)

  mkdir gitlab-exporter
  wget -O gitlab-exporter/index.js https://gitlab.com/gitlab-org/gl-performance/raw/10-5/index.js

  mkdir sitespeed-results

  if [[ -f .gitlab-urls.txt ]]; then
    sed -i -e 's@^@'"$CI_ENVIRONMENT_URL"'@' .gitlab-urls.txt
    docker run --shm-size=1g --rm -v "$(pwd)":/sitespeed.io sitespeedio/sitespeed.io:6.3.1 --plugins.add ./gitlab-exporter --outputFolder sitespeed-results .gitlab-urls.txt
  else
    docker run --shm-size=1g --rm -v "$(pwd)":/sitespeed.io sitespeedio/sitespeed.io:6.3.1 --plugins.add ./gitlab-exporter --outputFolder sitespeed-results "$CI_ENVIRONMENT_URL"
  fi

  mv sitespeed-results/data/performance.json performance.json
}

function delete() {
	track="${1-stable}"
	name="$RELEASE"

	if [[ "$track" != "stable" ]]; then
		name="$name-$track"
	fi

	helm uninstall "$name" || EXIT_CODE=$? && true
  echo ${EXIT_CODE}

  # It appears the default service account does not have permissions for this.
	if [[ ${CI_ENVIRONMENT_SLUG:0:6} == "review" ]]; then
	  echo "Deleting namespace $KUBE_NAMESPACE"
		kubectl delete namespace $KUBE_NAMESPACE --grace-period=0
	else
	  echo "Skipping namespace delete for slug $CI_ENVIRONMENT_SLUG and namespace $KUBE_NAMESPACE"
	fi
}
