# Scouting Nederland WordPress Theme Instructions

This is a classic WordPress theme with `theme.json`. Keep changes focused, preserve
the native template hierarchy, and follow the repository's `.editorconfig`.

## PHP and WordPress

- Preserve the `scoutingNederland` function prefix and existing public hooks.
- Sanitize input, validate capabilities and nonces where applicable, and escape
  output in its rendering context.
- Preserve compatibility with the Customizer, widgets, editor styles, and existing
  theme settings.
- Keep theme-owned CSS classes lowercase and hyphenated. Preserve WordPress core
  and editor class names as compatibility contracts.

## Assets and Releases

- Do not reformat or alter vendored files under `assets/vendor/` unless explicitly
  requested.
- Review `PUBLISHING.md` before changing bundled fonts, images, or release contents;
  redistribution and privacy constraints apply to some assets.
- Build distributable archives with `scripts/build-zip.ps1`. Do not commit generated
  ZIP files.

## Validation

- Run PHP syntax checks for changed PHP files.
- Keep `.github/workflows/` valid with Actionlint and preserve least-privilege
  workflow permissions and pinned third-party actions.
- Run WordPress Theme Check against the packaged theme when practical.
- Run `git diff --check` after edits and report checks that could not be run.
