# Working in the skeleton application

> Read this before adding or changing an Action, Domain, Responder, route, view or command, or when deciding whether a change belongs here or in the framework package.

This repository is the application that `composer create-project dillonsmart/tetherphp` installs. The framework is
**not** here — it is the `dillonsmart/tetherphp-core` Composer package, installed into
`vendor/dillonsmart/tetherphp-core`.

Because this is a `create-project` template, everything committed here is inherited by every application generated
from it. Keep it a starting point, not a showcase.

## Design constraints

TetherPHP is built on six core principles, summarised in `AGENTS.md` and carried in full by
`docs/agents/principles.md` in the `tetherphp-core` repository. The ones that shape this repository most:
**Explicit Over Magic** — `Request → Route → Action → Domain → Responder → Response` must stay traceable by reading —
and **One Obvious Way**, which is why the skeleton ships one convention rather than demonstrating several.

## Layout

```
app/
├── Actions/      # Actions\      — receive the Request, invoke a Domain, hand off to a Responder
├── Commands/     # Commands\     — console commands (created by make:command)
├── Domains/      # Domains\      — business logic, no HTTP knowledge
├── Responders/   # Responders\   — turn a result into a response (view or JSON)
├── Services.php  # App\Services  — what the application is made of; built in public/index.php
└── Views/        # Views\        — templates, partials, error pages
public/           # web root: index.php, compiled css/js
tests/            # Tests\ — Unit/ and Feature/
resources/css/    # Tailwind source
routes/web.php    # route definitions
routes/middleware.php  # what every request passes through
storage/          # logs and application storage
tether            # console entry point — a shim over vendor/bin/tether
```

**Every feature is a directory.** A feature is named, and what it does within that feature is named separately:

```
app/Actions/Home/Index.php            Actions\Home\Index
app/Domains/Home/Index.php            Domains\Home\Index
app/Domains/Home/Results/Page.php     Domains\Home\Results\Page
app/Responders/Home/Index.php         Responders\Home\Index
app/Views/pages/home/index.php
```

`tether make:feature Blog` writes one operation into that shape and `tether make:resource Post` writes seven — the
same layout either way, so **a feature grows by addition**: `tether make:action Blog Show` puts a second operation
beside the first and moves nothing. A Result lives with the Domain that returns it, so one feature owns one
directory under `Domains/`.

Actions, Domains and Responders are named for the operation. **A Result is named for its shape**, and shared by
every operation of the feature that answers the same way: `Collection` for many records, `Record` for one, `Written`
for a write that answers with a redirect, `Page` for a page with neither behind it.

## Request lifecycle

`public/index.php` loads the autoloader, builds the `Services` the application runs with, builds a `Router` and
applies `routes/web.php` to it, loads the middleware list from `routes/middleware.php`, and calls `send()` on the
`Response` that `Kernel::run()` returns. So **an Action must return a `Response`** — `send()` is the only place
anything is written to the client.

What the application is made of is **handed to the Kernel, not found by it**:

```php
$services = new Services(
    env: Env::fromFile(__DIR__ . '/../.env'),
    log: new Log(__DIR__ . '/../storage/logs'),
);

$middleware = (require __DIR__ . '/../routes/middleware.php')($services->env, $services->log);

new Kernel($router, $services, $middleware)->run()->send();
```

Which `.env` is read, where logs are written, what else the application has and what wraps a request are all
answered by reading this file. Change any of those lines — a different environment file per deployment, a log
directory outside the project, a second database — and nothing in the framework needs to know.

### How a Domain gets a dependency

`App\Services` is the application's own class — `final readonly`, one public property per thing the application
has. `Env` and `Log` are the two that `ServicesInterface` requires, because the Kernel runs on them: it reads
`APP_DEBUG`, logs what goes wrong, and installs both for the `env()` and `logger()` helpers. Everything else on the
class is yours. The Kernel constructs every Action with `($request, $services)`, and the Action hands its Domain the
pieces the Domain asks for:

```php
// app/Actions/Home/Index.php
public function __construct(protected Request $request, Services $services)
{
    $this->domain = new IndexDomain($services->env);
    $this->responder = new IndexResponder($request);
}

// app/Domains/Home/Index.php
public function __construct(private Env $env)
{
}
```

Three rules:

- **A Domain takes what it needs, never `Services`.** Its constructor is then the complete list of what it depends
  on, and `tests/Unit/HomeDomainTest.php` builds one with `new Env([...])` and no global. A Domain that took the
  whole object would depend on everything and say nothing. The same goes for `env()` and `logger()`: they exist for
  views, which nothing constructs; a Domain is handed its `Env`.
- **Add a dependency in two places.** A property on `Services` — `public PDO $db` — and its construction in
  `public/index.php` — `db: new PDO($env->get('DB_DSN') ?? '')`. Nothing else changes, and nothing is looked up by
  name. A class of your own that `Services` holds lives under `app/Services/` in the `App\Services` namespace.
- **Build it, don't make it lazy.** Everything in `Services` is constructed on every request, in the order written.
  Something expensive that most requests never touch should connect on first use — the way `Session` does —
  rather than the wiring growing closures.

`php tether inspect App\Services` lists what it provides, `inspect` on an Action shows it takes one, and
`php tether context` carries the class and its properties under `services`. None of them construct it.

What this does not cover: `routes/middleware.php` still takes `(Env, Log)`, because the console builds that list to
report it and cannot build the application's services. A middleware that needs a connection has no explicit route
to one yet.

`Kernel` then installs error and exception handlers, and routes.

### Middleware

`routes/middleware.php` lists what every request passes through, outermost first, in the order written:

```php
return function (Env $env, Log $log): array {
    return [
        new OverridesMethod(),
        new VerifyCsrfToken(new Session(), $log),
    ];
};
```

It sits beside `routes/web.php` because the two answer the same kind of question: `web.php` says where a request
goes, `middleware.php` says what it goes through on the way. `public/index.php` loads both.

A middleware is one method — `__invoke(Request $request, \Closure $next): Response`. Call `$next($request)` to
continue and you get the Response from the rest of the pipeline, to return, replace or add a header to. Return your
own Response without calling `$next` and nothing after it runs, which is how a guard refuses a request. Throwing an
`HttpException` works too, and is the same way an Action ends a request early.

Middleware wraps routing, not just the Action, so it runs for a request that goes on to 404 — and sees that 404 on
the way back out.

**The framework starts no session, checks no CSRF token and honours no `_method` field of its own accord.** The
skeleton composes `OverridesMethod` and `VerifyCsrfToken` in because most applications serve forms; an application
that does not — a token-authenticated API — deletes both lines and boots with no session at all. CSRF used to be
validated inside `Request`, so it could not be turned off.

`OverridesMethod` goes first so that `VerifyCsrfToken` logs the verb the form asked for rather than the POST it
arrived as. A browser form can only send GET or POST, so without it the router's `put()`, `patch()` and `delete()`
routes are unreachable from a page.

**Building a middleware must have no side effects.** `php tether routes`, `explain` and `context` build this list to
report what runs around a request, so a constructor that opens a connection or starts a session does it from a
terminal too. Do the work in `__invoke()`. `Session` starts on first use rather than on construction for this
reason, so holding one costs nothing.

`php tether explain /some/uri` shows the middleware a request passes through before the route it resolves to.

## ADR conventions

- An **Action** implements `ActionInterface`, takes the `Request` and the `Services` in its constructor, and returns
  a `Response`. It coordinates; it should not contain business logic or build markup. Dynamic route parameters are
  on the request as `$this->request->params['slug']` — never re-parse the URI.
- A **Domain** holds the logic and knows nothing about HTTP. What it needs — an `Env`, a `PDO` — arrives through its
  constructor from the Action. `handle()` returns a **`DomainResult`** — never an array. See below.
- A **Responder** renders — `view()` or `json()`, both returning a `Response`. Pass a status as `view($name, $data, 404)` rather than calling `http_response_code()`.

### Domains return a result, not an array

`Domain::handle()` used to return `array<string, mixed>`, and the Responder passed that array straight to the view,
where `extract()` turned its keys into template variables. So the array's keys *were* the view's variable names:
renaming `$tagline` in a template meant editing `Domains\Home`. That is the coupling the Responder exists to absorb,
and while it lasted the Responder did nothing but forward its argument.

A Domain now returns a `final readonly` value object under `Domains\<Feature>\Results\`, implementing
`TetherPHP\framework\Interfaces\DomainResult` (an empty marker — it exists so `handle()` and `Action::respond()`
have a type). The Responder translates it:

```php
// app/Domains/Home/Results/Page.php — named for the domain
final readonly class Page implements DomainResult
{
    public function __construct(public string $name, public string $description) {}
}

// app/Responders/Home/Index.php — the one place view variables are named
public function __invoke(Page $result): Response
{
    return $this->view('pages.home.index', [
        'appName' => $result->name,
        'tagline' => $result->description,
    ]);
}
```

Two rules follow, and both are the point of the change:

- **Only a Responder may name a view variable.** If a Domain knows a template calls something `$tagline`, the
  separation is gone again.
- **One result type per outcome.** A feature that can miss returns a different class when it misses, and the
  Responder picks the view and the status from the type it was handed. An array with a `found` flag in it is the
  shape this change exists to remove.

Generate the trio rather than hand-rolling it:

```bash
php tether make:feature <name>                  # one operation: Action, Domain, Result, Responder and view
php tether make:resource <name> [--uri=/posts]  # seven operations, the Results they share, and the views
php tether make:action <feature> <operation>
php tether make:domain <feature> <operation>    # writes the Result too — the Domain's return type names it
php tether make:responder <feature> <operation> # writes the view too — the Responder renders it
php tether make:command <name>
```

`<operation>` defaults to `Index`, so `make:action Blog` and `make:action Blog Index` are the same call.

Generating a piece at a time is the same writer as generating the whole feature, so the two cannot drift apart.
`make:action` on its own says which of the Domain and Responder it names do not exist yet, because an Action that
references a missing class fatals on the first request rather than at generation time.

### Asking the application about itself

```bash
php tether routes                   # the resolved table, with anything that would 500 marked
php tether explain /blog/hello      # the path that URI takes through the pipeline
php tether inspect Home             # what a class is in ADR terms, and what it takes
php tether context                  # the whole application as JSON, for agents and tooling
php tether serve                    # PHP's built-in server, pointed at public/
php tether test                     # forwards to the application's own PHPUnit
```

`explain` resolves the URI the way a request would — compared case-insensitively, query string dropped — and names
the parameters a dynamic route would capture, so it answers "why does this 404?" without reading the matcher.

`routes` and `context` both mark a route whose Action is missing or does not implement `ActionInterface`. That is
otherwise a 500 nobody sees until someone requests the page.

Where these link an Action to a Domain and Responder, they say **by convention** — an Action constructs its own in
its constructor and may use anything. They report what is on disk under the conventional name.

`php tether help <command>` prints what one command takes.

Generated commands land in `app/Commands/` under the `Commands\` namespace. That PSR-4 mapping must stay in
`composer.json` (and in `composer.local.json.example`) — without it `Console::registerCommands()` cannot autoload them
and they disappear from `php tether help` with no error at all.

### The `tether` file

`tether` in the project root holds no console code. `tetherphp-core` declares `bin/tether` in its `composer.json`, so
Composer writes a proxy to `vendor/bin/tether` on install, and the root file forwards to that proxy:

```php
$binary = __DIR__ . '/vendor/bin/tether';
// ...
return require $binary;
```

It exists so `php tether help` still works from the project root, and so a generated application does not carry its
own copy of the console bootstrap that would drift from the framework's. `php vendor/bin/tether help` is the same
program.

Two consequences:

- Changing how the console boots — argument parsing, the autoloader lookup, the exit code — is a change to
  `bin/tether` in `tetherphp-core`, not to this file.
- The shim resolves only against a core release that declares the `bin`. Against an older one `vendor/bin/tether` is
  never written and the shim exits 1 telling you to run `composer install`, so bump the constraint in `composer.json`
  when adopting it.

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

- Routes are **case-insensitive, but parameters are not normalised**. The Router compares segments with
  `strcasecmp` and captures `{param}` segments verbatim, so `/posts/My-Slug` matches `/posts/{slug}` and
  `params['slug']` is `My-Slug`. `Request::$uri` is never rewritten — it used to be lowercased by a property hook,
  which made routing a slug or a UUID impossible.
- A static route wins over a dynamic route of the same shape.
- A dynamic route only matches a URI with the **same number of `/`-separated segments**.
- `group()` requires a non-empty prefix and `{}` with an empty name throws — both are `InvalidArgumentException`.
- An unmatched route renders `app/Views/errors/404.php`; a matched route whose Action class does not exist renders
  `app/Views/errors/500.php`.

## Views

`Views\` maps to `app/Views/`. Error views live in `app/Views/errors/`; the framework ships fallbacks but the
application's own copies take precedence. `$router->view()` uses dot notation (`pages.terms` →
`app/Views/pages/terms.php`).

## Tests

```bash
php tether test                 # or composer test, or vendor/bin/phpunit
php tether test --filter=Home
```

Two suites, and the split is the ADR split:

- **`tests/Unit`** — a Domain or a Result on its own. No Kernel, no routing, no request. A Domain knows nothing about
  HTTP, so testing one needs none of it; that is what the separation buys.
- **`tests/Feature`** — a request through the real Kernel with the real `routes/web.php`, asserting on the `Response`
  it returns. `Tests\TestCase` gives you `get()`, `post()` and `send()`.

The base `TestCase` builds its own `Services` — with an `Env` and a `Log` of its own inside — rather than reading
the `.env` on disk, so a test states the settings it depends on and never writes into `storage/`. That is only
possible because the Kernel is handed them. Override `services()` to hand a feature a fake in place of a real
connection.

It composes **no middleware** by default, so writes are not CSRF-challenged and a feature test stays a single call —
a test that had to mint a token before it could POST would be testing the middleware rather than the feature.
Override `middleware()` to run against the real stack, as `tests/Feature/CsrfTest.php` does:

```php
protected function middleware(): array
{
    return (require __DIR__ . '/../../routes/middleware.php')($this->env(), $this->log());
}
```

A status assertion alone is not enough: the error view is served with a 200 whenever the status was never set, so
assert on the body too. `HomeTest` does.

## Environment

`.env` is required — `Env::fromFile()` throws, naming the path it looked at, if it is missing. Copy it first on a
fresh checkout:

```bash
cp .env.example .env
```

In a view, read values with `env('KEY')`, or `env('KEY', 'fallback')` for a default. A missing key with no default
returns `null` rather than throwing. `APP_DEBUG=true` turns on error display; anything else suppresses it.

`env()` is a one-line delegate to the `Env` that `public/index.php` built, and so is `logger()` to the `Log`. They
are for templates, which nothing constructs. A Domain is handed its `Env` by the Action — `$services->env` — and
reads it with `$this->env->get('KEY', 'fallback')`, so a unit test can give it one. There is no `Env::getInstance()`:
if you need an environment somewhere the Kernel has not booted — a script of your own — construct one and install
it with `Env::use(Env::fromFile($path))`.

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
| `App\Services` and what `public/index.php` builds into it    | here              |
| Routing, request, session, CSRF, logging, console, stubs     | `tetherphp-core`  |
| The console binary itself (`bin/tether`)                     | `tetherphp-core`  |

If a change needs framework code, see the linked-development guide (`docs/agents/linked-core-development.md`) — do not vendor-patch
`vendor/dillonsmart/tetherphp-core`, as it is overwritten on the next install.

## Keeping this guide current

These guides are part of the source. When a change makes anything above inaccurate — a new `app/` directory and its
PSR-4 mapping, a routing behaviour change, a new generator, a different asset pipeline — update this file in the same
commit, along with `README.md` and `composer.local.json.example` if the autoload roots moved.
