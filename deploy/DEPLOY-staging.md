# N3 — Azure staging deploy runbook

Companion to `HANDOFF-Fable.md` (env details). Artifacts in this dir:
`Dockerfile`, `entrypoint-azure.sh`, `sshd_config`, `deploy.sh`.

## Sequence

1. **Login + verify** (Nick, interactive): `az login --tenant 5255010f-5fc6-4e7b-8d8a-93481e0e0062`
   then `az account set --subscription f39024c5-e896-49f0-98ea-92266e0f0aa2`.
   Check RBAC: can we create an ACR / write app settings? (`deploy.sh verify`)
2. **Registry**: preferred = ACR Basic (~$5/mo) in RG `PSIA-NRM` → `az acr build`
   builds in the cloud (no local Docker on Ridge). Fallback: build on crosscut
   (10.15.25.25) and push to a private Docker Hub repo.
3. **MySQL prep** (needs admin password — Sean, or portal "Reset password"):
   - Recommend Sean downsizes to Burstable **before** load (§6 of handoff).
   - Create DB + app user (from inside VNet, or temp public firewall rule):
     `CREATE DATABASE wordpress; CREATE USER 'wp'@'%' IDENTIFIED BY '...';
      GRANT ALL ON wordpress.* TO 'wp'@'%';`
4. **App settings** (`deploy.sh configure`): WORDPRESS_DB_HOST/NAME/USER/PASSWORD,
   `WORDPRESS_TABLE_PREFIX=nrm_`,
   `WORDPRESS_CONFIG_EXTRA=define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);`,
   `WEBSITES_ENABLE_APP_SERVICE_STORAGE=true`, `WEBSITES_PORT=80`.
5. **VNet integration**: confirm the app is integrated with `vnet-wldeqcjg` so the
   container resolves the private MySQL FQDN (`deploy.sh verify` prints status).
6. **Point app at image** (`deploy.sh set-image`), restart, watch log stream.
7. **Install + import** (via `az webapp ssh` — sshd is baked into the image):
   ```
   wp core install --url=https://<staging-host> --title="PSIA Northern Rocky Mountain" \
     --admin_user=nrm-admin --admin_email=nd@nickdawson.net --prompt=admin_password --allow-root
   wp theme activate psia-nrm --allow-root
   wp plugin activate nrm-import --allow-root
   wp eval 'nrm_maybe_import_data();' --allow-root   # or just load /wp-admin once
   wp post list --post_type=nrm_member --format=count --allow-root   # expect 79
   ```
8. **Smoke test**: front page, member directory (79), events (12), schools (29),
   a member profile, /wp-admin login. Staging URL:
   `https://psia-nrm-website-gvcsgxcxdpaxg0hp.canadacentral-01.azurewebsites.net`

## Notes
- MySQL is TLS-required; `MYSQLI_CLIENT_SSL` client flag suffices (CA pem is in
  the image at `/usr/local/share/azure-mysql-ca.pem` if we later want strict verify).
- The official WP image already handles `X-Forwarded-Proto` from the App Service
  front end, so https URLs work behind TLS termination.
- Uploads persist under `/home/site/wp-content/uploads` (App Service storage).
- If Azure flags the MySQL major version (5.7 EOL Dec 2026): upgrade to 8.0
  before the import — empty server, zero risk now, painful later.
