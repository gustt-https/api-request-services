#!/usr/bin/env bash
# Restart Laravel API with raised upload limits for identity photos.
# Run on the host (outside Cursor sandbox): bash scripts/dev-api.sh
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

pkill -f 'artisan serve --host=0.0.0.0 --port=8000' 2>/dev/null || true
sleep 1

php -c php-dev.ini artisan serve --host=0.0.0.0 --port=8000
