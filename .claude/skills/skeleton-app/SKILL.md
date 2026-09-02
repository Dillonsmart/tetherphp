---
name: skeleton-app
description: Conventions for the TetherPHP skeleton application — the ADR (Action-Domain-Responder) structure, routing, views, console commands and asset builds. Use when adding or changing an Action, Domain, Responder, route, view or command in this repository, or when deciding whether a change belongs here or in the framework package.
---

# Working in the TetherPHP skeleton

This repository is the application that `composer create-project dillonsmart/tetherphp` installs. The framework is
**not** here — it is the `dillonsmart/tetherphp-core` Composer package, installed into
`vendor/dillonsmart/tetherphp-core`.

Because this is a `create-project` template, everything committed here is inherited by every application generated
from it. Keep it a starting point, not a showcase.

## Layout

```
app/
├── Actions/      # Actions\      — receive the Request, invoke a Domain, hand off to a Responder
├── Commands/     # Commands\     — console commands (created by make:command)
├── Domains/      # Domains\      — business logic, no HTTP knowledge
├── Responders/   # Responders\   — turn a result into a response (view or JSON)
└── Views/        # Views\        — templates, partials, error pages
public/           # web root: index.php, compiled css/js
resources/css/    # Tailwind source
routes/web.php    # route definitions
storage/          # logs and application storage
```

## Request lifecycle

`public/index.php` loads the autoloader, builds a `Router`, applies `routes/web.php` to it, and hands it to
`Kernel::run()`, whose return value is echoed. So **an Action must return a string** — it is the response body.

`Kernel` boots `Env`, defines `VERSION`/`VERSION_NAME`, installs error and exception handlers, starts a `Session` and
ensures a CSRF token exists, all before routing.

## ADR conventions

- An **Action** is invokable (`__invoke()`), takes the `Request` in its constructor, and returns a string. It
  coordinates; it should not contain business logic or build markup.
- A **Domain** holds the logic and knows nothing about HTTP.
- A **Responder** renders — `view()` or `json()`.

Generate the trio rather than hand-rolling it:

```bash
php tether make:feature <name>
php tether make:command <name>
```

Generated commands land in `app/Commands/` under the `Commands\` namespace. That PSR-4 mapping must stay in
`composer.json` (and in `composer.local.json.example`) — without it `Console::registerCommands()` cannot autoload them
and they disappear from `php tether help` with no error at all.

## Routing

`routes/web.php` returns a closure taking the `Router`:

```php
return function (Router $router) {
    $router->get('/', Home::class);
    $router->get('/docs/{page}', Docs::class);
    $router->view('/terms', 'pages.terms');   // renders a view with no Action
    $router->group('admin', function (Router $router) {
        $router->get('/users', Users::class);
    });
};
```

Things worth knowing before debugging a route:

- Request URIs are **lowercased** by `Request::$uri`'s property hook, so routes are case-insensitive and captured
  dynamic parameters arrive lowercased.
- A static route wins over a dynamic route of the same shape.
- A dynamic route only matches a URI with the **same number of `/`-separated segments**.
- `group()` requires a non-empty prefix and `{}` with an empty name throws — both are `InvalidArgumentException`.
- An unmatched route renders `app/Views/errors/404.php`; a matched route whose Action class does not exist renders
  `app/Views/errors/500.php`.

## Views

`Views\` maps to `app/Views/`. Error views live in `app/Views/errors/`; the framework ships fallbacks but the
application's own copies take precedence. `$router->view()` uses dot notation (`pages.terms` →
`app/Views/pages/terms.php`).

## Environment

`.env` is required — `Env::loadEnv()` throws if it is missing. Copy it first on a fresh checkout:

```bash
cp .env.example .env
```

Read values with `env('KEY')`. A missing key is logged and returns `null`. `APP_DEBUG=true` turns on error display;
anything else suppresses it.

## Assets

Tailwind, configured in `tailwind.config.js` to scan `app/Views/**/*.php`:

```bash
npm install
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch
```

New view directories outside `app/Views/` need adding to the `content` globs or their classes get purged.

## Running it

Any PHP server pointed at `public/` works:

```bash
php -S 127.0.0.1:8000 -t public
```

## Where a change belongs

| Change                                                     | Repository        |
| ----------------------------------------------------------- | ----------------- |
| Actions, Domains, Responders, views, routes, assets, `.env`  | here              |
| Routing, request, session, CSRF, logging, console, stubs     | `tetherphp-core`  |

If a change needs framework code, see the `linked-core-dev` skill — do not vendor-patch
`vendor/dillonsmart/tetherphp-core`, as it is overwritten on the next install.

## Keeping this skill current

These skills are part of the source. When a change makes anything above inaccurate — a new `app/` directory and its
PSR-4 mapping, a routing behaviour change, a new generator, a different asset pipeline — update this file in the same
commit, along with `README.md` and `composer.local.json.example` if the autoload roots moved.
