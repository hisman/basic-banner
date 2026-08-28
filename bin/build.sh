#!/usr/bin/env bash
set -euo pipefail

slug="basic-banner"
stage_only=false

for arg in "$@"; do
	case "$arg" in
		--no-zip) stage_only=true ;;
		*) echo "Unknown option: $arg" >&2; exit 1 ;;
	esac
done

npm run build
npx gulp

rm -rf "$slug" "$slug.zip"

rsync -a ./ "$slug/" \
	--exclude '.git/' \
	--exclude '.wordpress-org/' \
	--exclude 'node_modules/' \
	--exclude 'vendor/' \
	--exclude 'bin/' \
	--exclude 'src/' \
	--exclude 'tests/' \
	--exclude '.editorconfig' \
	--exclude '.gitattributes' \
	--exclude '.gitignore' \
	--exclude 'gulpfile.js' \
	--exclude 'package.json' \
	--exclude 'package-lock.json' \
	--exclude 'composer.json' \
	--exclude 'composer.lock' \
	--exclude 'README.md' \
	--exclude 'AGENTS.md' \
	--exclude 'CLAUDE.md' \
	--exclude '.phpcs.xml.dist' \
	--exclude '.travis.yml' \
	--exclude 'phpunit.xml.dist' \
	--exclude 'postcss.config.js'

if $stage_only; then
	echo "Staged to ./$slug/ (no zip)."
	exit 0
fi

zip -r "$slug.zip" "$slug"
rm -rf "$slug"
echo "Packaged: $slug.zip"
