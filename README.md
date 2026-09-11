<p align="center"><a href="https://tetherphp.com" target="_blank"><img src="https://tetherphp.com/og-image.png" width="600" alt="TetherPHP"></a></p>
<p align="center">
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/dt/dillonsmart/tetherphp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/v/dillonsmart/tetherphp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/l/dillonsmart/tetherphp" alt="License"></a>
</p>

## About TetherPHP

TetherPHP is a small PHP framework built on the Action-Domain-Responder pattern. Its goal is that a request can be
followed from route to response by reading the code — one Action per route, a Domain that knows no HTTP, and a
Responder that decides what a view is given.

It is opinionated about structure and deliberately narrow in scope: there is no ORM, no query builder, no validation
layer and no container. Those compose in as packages. What ships is routing, requests, responses, a middleware seam
and the console.

It is built in public — the [dev log](https://tetherphp.com/devlog) records what broke and why, and development is
posted on [X](https://x.com/DillonDevStuff).

## Features

- **ADR architecture** — one Action per route, and a pipeline you can trace by reading it
- **Routing** — five HTTP verbs, dynamic segments, route groups. Captured parameters arrive on the request exactly as
  they were sent, so slugs and UUIDs survive
- **Requests** — three sources of input, each read where it came from: `params` from the path, `query` from the query
  string, `payload` from the body, parsed for every verb
- **CRUD generation** — `make:resource` writes a whole resource: seven Actions, Domains and Responders, the Results
  they share, and the views
- **Middleware** — the seam everything composes onto. CSRF protection and method overriding ship with the framework
  and are opted into, so an API can leave them out and boot with no session at all
- **Introspection** — `routes`, `explain`, `inspect` and `context` report what an application actually does, the last
  as JSON for tooling and agents
- **Environment configuration** — `.env`, handed to the Kernel rather than found by it
- **Tailwind CSS** — pre-configured for styling
- **Logging** — to the `storage/` directory

## Requirements

- PHP 8.5 or higher
- Composer

## Installation

Install TetherPHP using Composer:

```bash
composer create-project dillonsmart/tetherphp ./
```

Copy the `.env.example` file to `.env` and configure your application settings:

```bash
cp .env.example .env
```

## Building Assets

TetherPHP uses Tailwind CSS for styling. Install dependencies and build the stylesheet:

```bash
npm install && npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch
```

## Project Structure

```
├── app/
│   ├── Actions/Home/Index.php          # one class per route
│   ├── Commands/                       # your console commands (make:command)
│   ├── Domains/Home/Index.php          # business logic, no HTTP
│   │   └── Home/Results/Page.php       # the value object the Domain returns
│   ├── Responders/Home/Index.php       # names the view's variables
│   └── Views/pages/home/index.php
├── public/                             # web root (index.php, compiled assets)
├── resources/                          # source assets (CSS)
├── routes/web.php                      # where a request goes
├── routes/middleware.php               # what it passes through
└── storage/                            # logs and application storage
```

**Every feature is a directory.** `Actions\Home\Index` lives at `app/Actions/Home/Index.php`, with its Domain,
Result and Responder in the matching places. A second route is a second class beside the first — `tether make:action
Home Show` — never a second method on the same one.

```bash
php tether make:feature Blog                   # one page, whole triple
php tether make:resource Post --uri=/posts     # a full CRUD resource
php tether routes                              # what is registered, and what wraps it
php tether explain /posts/12                   # resolve one URL the way a request would
```

The framework itself is not part of this repository. It is installed as the
[`dillonsmart/tetherphp-core`](https://github.com/Dillonsmart/tetherphp-core) Composer package and lives in
`vendor/dillonsmart/tetherphp-core`.

## Usage

The [documentation](https://tetherphp.com/docs) covers routing, requests, responders, middleware, CRUD and the
console. `php tether help` lists every command, and `php tether help <command>` explains one.

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
rm -rf vendor composer.local.lock && composer install
```
