FROM nginx:alpine

RUN sed -i 's/^http {/&\n    proxy_busy_buffers_size   256k;/g' /etc/nginx/nginx.conf && \
    sed -i 's/^http {/&\n    proxy_buffers   4 256k;/g' /etc/nginx/nginx.conf && \
    sed -i 's/^http {/&\n    proxy_buffer_size   128k;/g' /etc/nginx/nginx.conf && \
    sed -i 's/^http {/&\n    fastcgi_buffers 4 256k;/g' /etc/nginx/nginx.conf && \
    sed -i 's/^http {/&\n    fastcgi_buffer_size   128k;/g' /etc/nginx/nginx.conf

COPY _docker/nginx/conf.d /etc/nginx/conf.d/
COPY public /srv/api/public
