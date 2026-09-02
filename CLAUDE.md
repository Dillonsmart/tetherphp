# tetherphp

The TetherPHP skeleton application — the project `composer create-project dillonsmart/tetherphp` installs.

The framework is **not** in this repository. It is the `dillonsmart/tetherphp-core` Composer package, installed into
`vendor/dillonsmart/tetherphp-core`. Framework changes belong in that repository.

Everything committed here is inherited by every application generated from this template, so keep it a starting point.

## Skills

Read the relevant skill in `.claude/skills/` before working here:

- **skeleton-app** — ADR conventions, routing behaviour, views, environment, assets, and what belongs here versus in
  the framework package.
- **linked-core-dev** — developing against a local `tetherphp-core` checkout, and returning to the published package.

## Running it

```bash
cp .env.example .env          # Env::loadEnv() throws without it
composer install
php -S 127.0.0.1:8000 -t public
php tether help
```

## Keeping skills current

The skills in `.claude/skills/` are part of the source, not documentation about it. When a change makes one of them
inaccurate — a new `app/` directory and its PSR-4 mapping, a routing behaviour change, a different asset pipeline —
update the skill in the **same commit** as the change. A skill that has drifted is worse than no skill.

Note that the PSR-4 autoload roots are declared in both `composer.json` and `composer.local.json.example`; a change to
one must be mirrored in the other.
