# tetherphp

The TetherPHP skeleton application — the project `composer create-project dillonsmart/tetherphp` installs.

The framework is **not** in this repository. It is the `dillonsmart/tetherphp-core` Composer package, installed into
`vendor/dillonsmart/tetherphp-core`. Framework changes belong in that repository, not here.

Everything committed here is inherited by every application generated from this template, so keep it a starting
point rather than a showcase.

## The six core principles

Every design decision answers to these. The full charter lives in the `tetherphp-core` repository
(`docs/agents/principles.md`); the short form:

1. **Human First** — code should be obvious to a human. No cleverness for cleverness' sake.
2. **Agent Ready** — everything a human can understand, an agent should be able to understand. Predictable naming,
   explicit dependencies, consistent structure, machine-readable context.
3. **Explicit Over Magic** — `Request → Route → Action → Domain → Responder → Response` must be traceable without
   knowing implicit framework behaviour.
4. **One Obvious Way** — opinionated, one clear convention rather than five approaches. Adding a second way to do
   something means removing the first.
5. **Small & Composable** — the core does less, but does it well. Extra functionality composes in as packages.
6. **Tools Are Part of the Framework** — the CLI is first-class. A runtime feature is not finished until the tooling
   can show it.

## Setup

```bash
cp .env.example .env          # Env::loadEnv() throws without it
composer install
php -S 127.0.0.1:8000 -t public
php tether help
```

Assets are Tailwind, scanning `app/Views/**/*.php`:

```bash
npm install
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch
```

Requires **PHP >= 8.5**.

## Guides

Detailed working knowledge lives in `docs/agents/`. These are plain markdown and tool-agnostic — read the relevant
one before making changes:

- [`docs/agents/application.md`](docs/agents/application.md) — ADR conventions, routing behaviour, views,
  environment, assets, and what belongs here versus in the framework package.
- [`docs/agents/linked-core-development.md`](docs/agents/linked-core-development.md) — developing against a local
  `tetherphp-core` checkout, and returning to the published package.

## Structure

```
app/Actions/      Actions\      receive the Request, invoke a Domain, hand off to a Responder
app/Commands/     Commands\     console commands (created by make:command)
app/Domains/      Domains\      business logic, no HTTP knowledge
app/Responders/   Responders\   turn a result into a response
app/Views/        Views\        templates, partials, error pages
public/                         web root: index.php, compiled assets
routes/web.php                  route definitions
storage/                        logs and application storage
```

An Action implements `ActionInterface` and **returns a `Response`**. `Kernel::run()` returns one too, and
`public/index.php` calls `send()` on it — that is the only place anything is written to the client.

Route parameters arrive on the request: `$this->request->params['slug']`. Do not re-parse the URI.

## Things that bite

- **Request URIs are lowercased** by a property hook, so routes are case-insensitive and captured dynamic parameters
  arrive lowercased.
- A **static route wins** over a dynamic route of the same shape, and a dynamic route only matches a URI with the
  same number of `/`-separated segments.
- The PSR-4 roots are declared in **both** `composer.json` and `composer.local.json.example`. A change to one must be
  mirrored in the other, or classes resolve in one mode and not the other.
- Without the `Commands\` mapping, `Console::registerCommands()` cannot autoload generated commands and they vanish
  from `php tether help` with no error at all.
- `composer.lock` is deliberately **not** committed — `create-project` resolves fresh.

## Where a change belongs

| Change                                                      | Repository        |
| ------------------------------------------------------------ | ----------------- |
| Actions, Domains, Responders, views, routes, assets, `.env`   | here              |
| Routing, request, session, CSRF, logging, console, stubs      | `tetherphp-core`  |

Never patch `vendor/dillonsmart/tetherphp-core` — it is overwritten on the next install. See the **linked-core-dev**
skill for changing framework code alongside application code.

## Keeping documentation current

The guides in `docs/agents/` are part of the source, not documentation about it. When a change makes one of them
inaccurate — a new `app/` directory and its PSR-4 mapping, a routing behaviour change, a different asset pipeline —
update the skill in the **same commit** as the change, along with this file and `README.md` where they are affected.
A guide that has drifted is worse than no guide.
