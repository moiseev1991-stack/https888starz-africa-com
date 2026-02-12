#!/usr/bin/env bash
# Static export: mirror local WordPress to dist/
# Prerequisite: WP running at BASE_URL (e.g. http://localhost:8080)
# Usage: ./scripts/export-static.sh [BASE_URL] [OUT_DIR]

set -e
BASE_URL="${1:-http://localhost:8080}"
OUT_DIR="${2:-dist}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
DIST_PATH="$PROJECT_ROOT/$OUT_DIR"

cd "$PROJECT_ROOT"
rm -rf "$DIST_PATH"
mkdir -p "$DIST_PATH"

echo "Mirroring $BASE_URL -> $DIST_PATH ..."
wget --mirror --page-requisites --convert-links --no-host-directories --adjust-extension \
  --restrict-file-names=windows \
  --reject '*.php*' \
  --exclude-directories=wp-admin,wp-login,wp-includes,cgi-bin \
  --user-agent="StaticExport/1.0" \
  --directory-prefix="$DIST_PATH" \
  --no-check-certificate \
  "$BASE_URL" || true

# Replace base URL in HTML for deployment (relative links)
REPLACE_FROM="${BASE_URL%/}"
find "$DIST_PATH" -name "*.html" -type f -exec sed -i.bak "s|$REPLACE_FROM||g" {} \; 2>/dev/null || true
find "$DIST_PATH" -name "*.html.bak" -delete 2>/dev/null || true

# URL list
URLS_FILE="$PROJECT_ROOT/URLS.txt"
: > "$URLS_FILE"
find "$DIST_PATH" -name "*.html" -type f | while read -r f; do
  rel="${f#$DIST_PATH/}"
  rel="${rel//index.html/}"
  [ -z "$rel" ] && echo "/" >> "$URLS_FILE" || echo "/$rel" >> "$URLS_FILE"
done
sort -u -o "$URLS_FILE" "$URLS_FILE"

echo "Export done. Output: $DIST_PATH. URL list: URLS.txt"
