# PSIA-NRM WordPress — staging image for Azure App Service (PSIA-NRM-Website)
# Matches the prototype runtime: WP 6.7 / PHP 8.3 / Apache.
# Build context = repo root:  docker build -f deploy/Dockerfile -t psia-nrm-wp .
FROM wordpress:6.7-php8.3-apache

# openssh-server: App Service "SSH" (Kudu web SSH / az webapp ssh) requires the
# container to run sshd on 2222 with the documented config. wp-cli: lets us do
# core install + plugin/theme activation + import from inside the VNet, since
# the MySQL server is reachable only privately.
RUN apt-get update \
 && apt-get install -y --no-install-recommends openssh-server less mariadb-client \
 && rm -rf /var/lib/apt/lists/* \
 && mkdir -p /run/sshd \
 && curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
      -o /usr/local/bin/wp \
 && chmod +x /usr/local/bin/wp

COPY deploy/sshd_config /etc/ssh/sshd_config

# Azure Database for MySQL flexible server requires TLS. The roots Azure uses
# (DigiCert Global Root CA/G2, Microsoft RSA Root CA 2017) already ship in
# Debian's ca-certificates — bundle whichever are present.
RUN cat /etc/ssl/certs/DigiCert_Global_Root_CA.pem \
        /etc/ssl/certs/DigiCert_Global_Root_G2.pem \
        /etc/ssl/certs/Microsoft_RSA_Root_Certificate_Authority_2017.pem \
        2>/dev/null > /usr/local/share/azure-mysql-ca.pem \
 && test -s /usr/local/share/azure-mysql-ca.pem \
 && chmod 0444 /usr/local/share/azure-mysql-ca.pem

# Bake the site into the WP source tree; the official entrypoint copies
# /usr/src/wordpress into /var/www/html on container start.
COPY wordpress/themes/                /usr/src/wordpress/wp-content/themes/psia-nrm/
COPY wordpress/plugins/nrm-import.php /usr/src/wordpress/wp-content/plugins/nrm-import.php
COPY wordpress/plugins/nrm-forms.php  /usr/src/wordpress/wp-content/plugins/nrm-forms.php
COPY wordpress/mu-plugins/            /usr/src/wordpress/wp-content/mu-plugins/

# Seed data at the exact paths nrm-import.php reads. The local files are mode
# 600 and COPY preserves that — open them up so www-data can read them.
COPY wordpress/people.json wordpress/events.json wordpress/pages.json wordpress/cert-updates.json wordpress/member-photos.json /var/www/
RUN chmod 0444 /var/www/*.json

# Uploads live on the App Service persistent /home share
# (WEBSITES_ENABLE_APP_SERVICE_STORAGE=true) so media survives redeploys.
RUN rm -rf /usr/src/wordpress/wp-content/uploads \
 && ln -s /home/site/wp-content/uploads /usr/src/wordpress/wp-content/uploads

COPY deploy/entrypoint-azure.sh /usr/local/bin/entrypoint-azure.sh
RUN chmod +x /usr/local/bin/entrypoint-azure.sh

EXPOSE 80 2222
ENTRYPOINT ["entrypoint-azure.sh"]
CMD ["apache2-foreground"]
