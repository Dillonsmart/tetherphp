---
name: linked-core-dev
description: Point this application at a local tetherphp-core checkout so framework and application changes can be made together, and switch back to the published package. Use when a task needs framework code changed, when framework edits appear to have no effect, or when composer install unexpectedly replaces the linked core.
---

# Developing against a local tetherphp-core

The framework lives in the separate `dillonsmart/tetherphp-core` repository and is normally installed from Packagist.
To change framework and application code together, install core from a local checkout instead.

## Setup

Clone both repositories as **siblings**:

```
<workspace>/
├── tetherphp/          # this repository
└── tetherphp-core/     # the framework package
```

Then:

```bash
cp composer.local.json.example composer.local.json
COMPOSER=composer.local.json composer update
```

`composer.local.json` is a complete replacement manifest (Composer's `COMPOSER` env var swaps the whole file, it does
not merge). It declares a `path` repository at `../tetherphp-core` with `"symlink": true` and requires the package as
`"*"`, so any local branch satisfies it.

The result is a symlink:

```
vendor/dillonsmart/tetherphp-core -> ../../../tetherphp-core/
```

Edits in the core checkout take effect immediately — no reinstall, no copy step.

Confirm the link is live before trusting a framework change:

```bash
php -r 'require "vendor/autoload.php";
  echo (new ReflectionClass(TetherPHP\Router::class))->getFileName(), "\n";'
```

It must print a path inside the sibling `tetherphp-core` checkout. If it prints something under `vendor/` that is not a
symlink, the overlay is not in effect and you are editing files that the next `composer install` will discard.

## Daily use

Every Composer command must carry the overlay, or Composer falls back to `composer.json` and reinstalls the published
package over the symlink:

```bash
COMPOSER=composer.local.json composer update
COMPOSER=composer.local.json composer dump-autoload
```

An alias helps:

```bash
alias composer-local='COMPOSER=composer.local.json composer'
```

A plain `composer install` in this directory is the usual cause of "my framework edits stopped working".

## What is and is not committed

| File                          | Tracked | Purpose                                    |
| ----------------------------- | ------- | ------------------------------------------ |
| `composer.json`               | yes     | the real published dependency              |
| `composer.local.json.example` | yes     | the template for the linked setup          |
| `composer.local.json`         | no      | your local overlay                         |
| `composer.local.lock`         | no      | the overlay's lock                         |
| `composer.lock`               | no      | not shipped — `create-project` resolves fresh |

So the linked setup can never leak into a commit, and `composer.json` always describes what a real user installs.

**The overlay duplicates the `autoload` block from `composer.json`.** They are separate files with no merging, so a
change to the PSR-4 roots in one must be mirrored in `composer.json`, `composer.local.json.example` **and** your own
`composer.local.json`. Forgetting the overlay produces class-not-found errors that only appear in linked mode.

## Returning to the published package

```bash
rm -rf vendor composer.local.json composer.local.lock
composer install
```

Do this before testing anything release-shaped. Path resolution inside the framework differs between a symlinked
checkout and a real `vendor/` install, so a linked checkout cannot prove that a framework change works for an actual
consumer — see the `core-release` skill in the core repository.

## Never patch vendor

`vendor/dillonsmart/tetherphp-core` is either a symlink to the sibling checkout or a Composer-managed copy. Editing the
copy is discarded on the next install; editing through the symlink is really editing the core repository, so commit it
there.

## Keeping this skill current

If the linked-development mechanism changes — a different overlay filename, a merge plugin replacing the manual
duplication, a change to what is gitignored — update this file and `composer.local.json.example` in the same commit,
along with the "Working on the framework itself" section of `README.md`.
