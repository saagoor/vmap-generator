#!/bin/sh
set -e

echo "🎉 Deploying application..."

(git checkout main) || true
(git restore .) && (git clean -f -d)
git fetch && git rebase

docker compose build
docker compose restart

echo "🚀 Deployment successfull!"
