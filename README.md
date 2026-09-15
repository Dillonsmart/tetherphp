<p align="center"><a href="https://tetherphp.com" target="_blank"><img src="https://tetherphp.com/og-image.png" width="600" alt="TetherPHP"></a></p>
<p align="center">
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/dt/dillonsmart/tetherphp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/v/dillonsmart/tetherphp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/l/dillonsmart/tetherphp" alt="License"></a>
</p>

## Why

Most of the time spent on an application is spent reading it, and more and more often the reader is not the
person who wrote it. It is a colleague picking up the code, or an AI agent working on it.

Frameworks tend to make that harder than it needs to be. Things get resolved by conventions nobody wrote down,
dependencies get pulled from a container, and a request passes through layers of indirection before it reaches your
code. If you know the framework's folklore, none of that is a problem. If you don't, every step is a "how did that
happen?"

TetherPHP exists so that a request can be followed from route to response just by reading the code. If something
matters, it is visible. A file's location follows from its name. A class is handed what it depends on. And the
console can show you what the application does, so nobody has to guess. What makes the code obvious to a person
makes it obvious to an agent too.

## What it is

A small PHP framework built on the Action-Domain-Responder pattern. Each route has one Action, which calls a Domain
that knows nothing about HTTP, and hands the result to a Responder that decides what the view gets.

It is opinionated about structure and deliberately narrow in scope. There is no ORM, no query builder, no validation
layer and no container — those can be added as packages. What ships is routing, requests, responses, middleware and
the console.

It is built in public. The [dev log](https://tetherphp.com/devlog) records what broke and why, and development is
posted on [X](https://x.com/DillonDevStuff).

## Features

- **ADR architecture** — one Action per route, and a request path you can trace by reading it
- **Routing** — `get`, `post`, `put`, `patch` and `delete`, dynamic segments and route groups. Captured parameters
  reach your Action exactly as they were sent, so slugs and UUIDs survive
- **Requests** — path parameters, the query string and the request body are each available where you would expect:
  `$request->params`, `$request->query` and `$request->payload`. The body is parsed for every verb, not just POST
- **CRUD generation** — `make:resource` writes a whole resource: seven Actions, Domains and Responders, the Results
  they share, and the views
- **Middleware** — CSRF protection and `_method` overriding ship with the framework, but you opt into them in
  `routes/middleware.php`. An API can leave them out and boot with no session at all
- **Introspection** — `routes`, `explain`, `inspect` and `context` report what the application actually does. The
  last one prints JSON, for tooling and agents
- **Environment** — a `.env` file, handed to the Kernel by `public/index.php` rather than found by the framework
- **Dependencies without a container** — `app/Services.php` lists what the application is made of, `public/index.php`
  builds it, and every Action is handed it to pass its Domain what the Domain needs
- **Tailwind CSS** — pre-configured
- **Logging** — to `storage/logs/`

## Requirements

- PHP 8.5 or higher
- Composer

## Getting started

Create a project with Composer, then copy the example environment file:

```bash
composer create-project dillonsmart/tetherphp ./
cp .env.example .env
```

Run it on PHP's built-in server, or in Docker:

```bash
php tether serve                # http://127.0.0.1:8000
docker compose up --build       # http://localhost:8000
```

`php tether test` runs the test suite, and `php tether help` lists every command.

## Building assets

Styling is Tailwind CSS. Install the dependencies and build the stylesheet:

```bash
npm install && npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch
```

## Project structure

```
├── app/
│   ├── Actions/Home/Index.php          # one class per route
│   ├── Commands/                       # your console commands (make:command)
│   ├── Domains/Home/Index.php          # business logic, no HTTP
│   ├── Domains/Home/Results/Page.php   # the value object the Domain returns
│   ├── Responders/Home/Index.php       # names the view's variables
│   ├── Services.php                    # what the application is made of; built in public/index.php
│   └── Views/pages/home/index.php
├── public/                             # web root (index.php, compiled assets)
├── resources/                          # source assets (CSS)
├── routes/web.php                      # where a request goes
├── routes/middleware.php               # what it passes through
├── storage/                            # logs and application storage
├── tests/                              # Unit (a Domain alone) and Feature (through the Kernel)
└── tether                              # the console
```

**Every feature is a directory.** `Actions\Home\Index` lives at `app/Actions/Home/Index.php`, with its Domain,
Result and Responder in the matching places. A second route is a second class beside the first — `php tether
make:action Home Show` — never a second method on the same one.

The console generates that structure for you, and can report on it:

```bash
php tether make:feature Blog                   # one page: Action, Domain, Result, Responder and view
php tether make:resource Post --uri=/posts     # a full CRUD resource
php tether routes                              # what is registered, and what wraps it
php tether explain /posts/12                   # resolve one URL the way a request would
```

The framework itself is not part of this repository. It is installed as the
[`dillonsmart/tetherphp-core`](https://github.com/Dillonsmart/tetherphp-core) Composer package and lives in
`vendor/dillonsmart/tetherphp-core`.

## Documentation

The [documentation](https://tetherphp.com/docs) covers routing, requests, responders, middleware, CRUD and the
console. `php tether help <command>` explains any one command.

## Working on the framework itself

Framework changes belong in the [tetherphp-core](https://github.com/Dillonsmart/tetherphp-core) repository, not here.
To develop both together, clone them as siblings and point this application at your local core checkout:

```
~/your-projects/
├── tetherphp/          # this repository
└── tetherphp-core/     # the framework package
```

```bash
cp composer.local.json.example composer.local.json
COMPOSER=composer.local.json composer update
```

That installs the core package as a **symlink** to `../tetherphp-core`, so edits there take effect immediately with no
reinstall. `composer.local.json` and `composer.local.lock` are gitignored, so the linked setup never leaks into a
commit; `composer.json` continues to describe the real published dependency.

An alias helps:

```bash
alias composer-local='COMPOSER=composer.local.json composer'
```

To go back to the published package, remove the overlay and reinstall:

```bash
rm -rf vendor composer.local.json composer.local.lock && composer install
```
