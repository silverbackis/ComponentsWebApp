#bin/bash
docker-compose -f docker-compose.yaml -f docker-compose-prod.yaml up -d --force-recreate
