<p align="center"><a href="https://tetherphp.com" target="_blank"><img src="https://tetherphp.com/og-image.png" width="600" alt="TetherPHP"></a></p>
<p align="center">
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/dt/dillonsmart/tetherphp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/v/dillonsmart/tetherphp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/l/dillonsmart/tetherphp" alt="License"></a>
</p>
<p align="center"><em>Built with the help of <a href="https://claude.com/claude-code">Claude Code</a>, in public.</em></p>

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

It is built in public, with Claude Code. An AI agent writes much of the code; the reasoning behind each change is
in the commit messages, and the [dev log](https://tetherphp.com/devlog) records what broke and why. Development is
posted on X at [@DillonDevStuff](https://x.com/DillonDevStuff).

## What it looks like

This is the home page of a fresh install, end to end. A route names an Action:

```php
// routes/web.php
$router->get('/', Actions\Home\Index::class);
```

The Action is constructed with the request and the application's services, builds its Domain and Responder, and
connects them. It coordinates; it holds no logic and builds no markup:

```php
// app/Actions/Home/Index.php
class Index extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        $this->domain = new IndexDomain($services->env);
        $this->responder = new IndexResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
```

The Domain does the work. It knows nothing about HTTP, takes what it needs through its constructor, and returns a
value object rather than an array:

```php
// app/Domains/Home/Index.php
class Index extends Domain
{
    public function __construct(private readonly Env $env)
    {
    }

    public function handle(): Page
    {
        return new Page(
            name: $this->env->get('APP_NAME', 'TetherPHP'),
            description: 'An application built with TetherPHP.',
        );
    }
}
```

The Responder is the only place the view's variables are named. Rename `$tagline` in the template and the Domain
does not change:

```php
// app/Responders/Home/Index.php
class Index extends Responder
{
    public function __invoke(Page $result): Response
    {
        return $this->view('pages.home.index', [
            'appName' => $result->name,
            'tagline' => $result->description,
        ]);
    }
}
```

Nothing above is resolved by convention, discovered by scanning, or pulled from a container. Every arrow in
`Request → Route → Action → Domain → Responder → Response` is a line you can point at.

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

- PHP 8.5 or higher — property hooks and `new` without parentheses are used throughout, so 8.4 fatals rather than
  degrades
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

## Naming things

TetherPHP is opinionated about names, because a name is how you find a file and how the console finds it. There is
one way, the generators write it, and the rules fit in a table. `Note` is the feature and `Store` is the operation
throughout:

| Thing | Rule | Example |
| --- | --- | --- |
| Feature | a singular noun in PascalCase; one directory of that name under `Actions/`, `Domains/` and `Responders/` | `Note` |
| Operation | a verb in PascalCase; **one class per operation, with the same name in all three layers** | `Actions\Note\Store`, `Domains\Note\Store`, `Responders\Note\Store` |
| CRUD operations | the seven verbs `make:resource` writes, and no others | `Index` `Create` `Store` `Show` `Edit` `Update` `Destroy` |
| Result | named for its **shape**, never for the operation; `final readonly`, under `Results/`, shared by every operation that answers the same way | `Domains\Note\Results\Record` — used by `Show` and `Edit` alike |
| The result shapes | `Collection` many, `Record` one, `Written` a change, `Invalid` a refusal, `Page` none of those | `handle(): Written\|Invalid` |
| Collaborator | a **noun** at the root of the feature's Domain namespace: shared by the operations, routed to by nothing | `Domains\Note\Notes` (the queries), `Domains\Note\Attributes` (the rules) |
| View | `app/Views/pages/<feature>/<operation>.php`, both lowercase; referred to in dot notation | `pages/note/create.php`, `$this->view('pages.note.create', …)` |
| Partial | `app/Views/partials/<name>.php`, kebab-case | `partials/note-form.php` |
| Error view | `app/Views/errors/<status>.php` | `errors/404.php` |
| Route | kebab-case URI, plural for a resource, `{param}` for a segment; `make:resource --uri` sets it | `/notes`, `/notes/{id}`, `/notes/{id}/edit` |
| Command | `app/Commands/<Name>Command.php`; invoked in kebab-case, or namespaced with a colon | `DbSchemaCommand` → `php tether db:schema` |
| Services | one class, `App\Services` at `app/Services.php`; a class of your own that it holds goes under `app/Services/` | `App\Services\Mailer` |
| Test | `tests/Unit/` for a Domain or Result on its own, `tests/Feature/` for a request through the Kernel; `<Subject>Test.php` | `tests/Unit/AttributesTest.php`, `tests/Feature/NotesTest.php` |
| Env key | `UPPER_SNAKE_CASE` in `.env`, read with `$env->get('DB_DSN')` | `APP_NAME`, `DB_DSN` |

Three of those carry the rest:

- **Same name, three layers.** A route names an Action; the Action's name is the Domain's name is the Responder's
  name. That one-to-one path is what lets `php tether inspect Note\Store` show you all three, and why a Domain's
  operations never move into a subdirectory.
- **Verb or noun.** In `Domains/Note/`, a verb is an operation and a noun is a collaborator. You can tell which is
  which from the directory listing.
- **A Result is a shape.** Seven operations do not need seven result classes; they need four. If you find yourself
  writing `Results\Show`, stop.

The generators enforce all of it — `php tether make:action Note Archive` puts the file where the table says — and
`php tether context` reports the conventions as JSON for anything that reads them by machine.

## Documentation

The [documentation](https://tetherphp.com/docs) covers routing, requests, responders, middleware, services, CRUD and
the console. `php tether help <command>` explains any one command.

## A complete example

This skeleton is a starting point, so its one feature does nothing. To see what a feature looks like with a database
behind it, a form in front of it and input to refuse, read
[**tetherphp-demo**](https://github.com/Dillonsmart/tetherphp-demo): a notes application built from this skeleton
with `tether make:resource`, using SQLite through `Services`, with validation, a 422 that carries the form back, and
feature tests through the real Kernel against an in-memory database. Its README says what to read in what order.

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
