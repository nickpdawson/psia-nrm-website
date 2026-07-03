#!/bin/bash
set -euo pipefail

# Uploads target on the persistent /home share (the image symlinks
# wp-content/uploads here). Works locally too — it's just a normal dir then.
mkdir -p /home/site/wp-content/uploads
chown -R www-data:www-data /home/site/wp-content

# App Service web SSH convention: sshd on 2222, root password "Docker!".
# Port 2222 is only reachable through the Kudu/SCM channel, never the internet.
echo "root:Docker!" | chpasswd
/usr/sbin/sshd

exec docker-entrypoint.sh "$@"
