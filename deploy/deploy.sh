#!/usr/bin/env bash
# N3 staging deploy helper — PSIA-NRM WordPress on Azure App Service.
# Usage: deploy.sh {verify|acr-create|acr-build|configure|set-image|logs}
set -euo pipefail

SUB="f39024c5-e896-49f0-98ea-92266e0f0aa2"
RG="PSIA-NRM"
APP="PSIA-NRM-Website"
ACR="${ACR_NAME:-psianrmacr}"          # must be globally unique, lowercase
IMAGE="psia-nrm-wp"
TAG="${TAG:-$(git -C "$(dirname "$0")/.." rev-parse --short HEAD 2>/dev/null || echo latest)}"
DB_HOST="psia-nrm-website-server.mysql.database.azure.com"
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

az account set --subscription "$SUB"

case "${1:-}" in
  verify)
    echo "== identity =="; az account show --query '{user:user.name, sub:name}' -o table
    echo "== role assignments =="
    az role assignment list --assignee "$(az ad signed-in-user show --query id -o tsv 2>/dev/null || az account show --query user.name -o tsv)" --all -o table 2>/dev/null || echo "(cannot enumerate roles — try a write op to test)"
    echo "== app service =="; az webapp show -g "$RG" -n "$APP" --query '{state:state, host:defaultHostName, image:siteConfig.linuxFxVersion}' -o table
    echo "== vnet integration =="; az webapp vnet-integration list -g "$RG" -n "$APP" -o table
    echo "== mysql =="; az mysql flexible-server show -g "$RG" -n psia-nrm-website-server --query '{version:version, sku:sku.name, tier:sku.tier, public:network.publicNetworkAccess}' -o table
    ;;
  acr-create)
    az acr create -g "$RG" -n "$ACR" --sku Basic --admin-enabled true -o table
    ;;
  acr-build)
    az acr build -r "$ACR" -t "$IMAGE:$TAG" -t "$IMAGE:latest" -f "$REPO_ROOT/deploy/Dockerfile" "$REPO_ROOT"
    ;;
  configure)
    : "${WP_DB_PASSWORD:?set WP_DB_PASSWORD env var first}"
    az webapp config appsettings set -g "$RG" -n "$APP" --settings \
      WORDPRESS_DB_HOST="$DB_HOST" \
      WORDPRESS_DB_NAME="psia-nrm-website-database" \
      WORDPRESS_DB_USER="${WP_DB_USER:-wp}" \
      WORDPRESS_DB_PASSWORD="$WP_DB_PASSWORD" \
      WORDPRESS_TABLE_PREFIX="nrm_" \
      WORDPRESS_CONFIG_EXTRA="if (!empty(\$_SERVER['HTTP_X_FORWARDED_PROTO']) && \$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') { \$_SERVER['HTTPS'] = 'on'; } define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL); if (!empty(\$_SERVER['HTTP_HOST']) && preg_match('/^[a-z0-9.-]+\$/i', \$_SERVER['HTTP_HOST'])) { define('WP_HOME', 'https://' . \$_SERVER['HTTP_HOST']); define('WP_SITEURL', 'https://' . \$_SERVER['HTTP_HOST']); }" \
      WEBSITES_ENABLE_APP_SERVICE_STORAGE=true \
      WEBSITES_PORT=80 \
      -o none && echo "app settings set (password hidden)"
    ;;
  set-image)
    # Pull auth is via the app's managed identity (AcrPull role on the ACR,
    # acrUseManagedIdentityCreds=true) — do NOT pass registry credentials here;
    # a bare container-set with creds omitted used to wipe the stored password
    # and 503 the site. Managed identity has no password to wipe.
    az webapp config container set -g "$RG" -n "$APP" \
      --container-image-name "$ACR.azurecr.io/$IMAGE:$TAG" -o none
    az webapp restart -g "$RG" -n "$APP"
    echo "deployed $IMAGE:$TAG — follow with: $0 logs"
    ;;
  logs)
    az webapp log tail -g "$RG" -n "$APP"
    ;;
  *)
    echo "usage: $0 {verify|acr-create|acr-build|configure|set-image|logs}"; exit 1
    ;;
esac
