# Working in the skeleton application

> Read this before adding or changing an Action, Domain, Responder, route, view or command, or when deciding whether a change belongs here or in the framework package.

This repository is the application that `composer create-project dillonsmart/tetherphp` installs. The framework is
**not** here — it is the `dillonsmart/tetherphp-core` Composer package, installed into
`vendor/dillonsmart/tetherphp-core`.

Because this is a `create-project` template, everything committed here is inherited by every application generated
from it. Keep it a starting point, not a showcase.

## Design constraints

TetherPHP is built on six core principles, summarised in `AGENTS.md` and carried in full by the
**tetherphp-principles** skill in the `tetherphp-core` repository. The ones that shape this repository most:
**Explicit Over Magic** — `Request → Route → Action → Domain → Responder → Response` must stay traceable by reading —
and **One Obvious Way**, which is why the skeleton ships one convention rather than demonstrating several.

## Layout

```
app/
├── Actions/      # Actions\      — receive the Request, invoke a Domain, hand off to a Responder
├── Commands/     # Commands\     — console commands (created by make:command)
├── Domains/      # Domains\      — business logic, no HTTP knowledge
│   └── Results/  # Domains\Results\ — the value objects Domains return
├── Responders/   # Responders\   — turn a result into a response (view or JSON)
└── Views/        # Views\        — templates, partials, error pages
public/           # web root: index.php, compiled css/js
tests/            # Tests\ — Unit/ and Feature/
resources/css/    # Tailwind source
routes/web.php    # route definitions
routes/middleware.php  # what every request passes through
storage/          # logs and application storage
tether            # console entry point — a shim over vendor/bin/tether
```

## Request lifecycle

`public/index.php` loads the autoloader, builds a `Router`, applies `routes/web.php` to it, constructs the `Env` and
the `Log` the application runs with, and calls `send()` on the `Response` that `Kernel::run()` returns. So **an Action
must return a `Response`** — `send()` is the only place anything is written to the client.

The environment and the log are **handed to the Kernel, not found by it**:

```php
$env = Env::fromFile(__DIR__ . '/../.env');
$log = new Log(__DIR__ . '/../storage/logs');

new Kernel($router, $env, $log)->run()->send();
```

Which `.env` is read and where logs are written are answered by reading this file. Change either line — a different
environment file per deployment, a log directory outside the project — and nothing in the framework needs to know.

`Kernel` then installs error and exception handlers, and routes.

### Middleware

`routes/middleware.php` lists what every request passes through, outermost first, in the order written:

```php
return function (Env $env, Log $log): array {
    return [
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

**The framework starts no session and checks no CSRF token of its own accord.** The skeleton composes
`VerifyCsrfToken` in because most applications serve forms; an application that does not — a token-authenticated API
— deletes the line and boots with no session at all. CSRF used to be validated inside `Request`, so it could not be
turned off.

**Building a middleware must have no side effects.** `php tether routes`, `explain` and `context` build this list to
report what runs around a request, so a constructor that opens a connection or starts a session does it from a
terminal too. Do the work in `__invoke()`. `Session` starts on first use rather than on construction for this
reason, so holding one costs nothing.

`php tether explain /some/uri` shows the middleware a request passes through before the route it resolves to.

## ADR conventions

- An **Action** implements `ActionInterface`, takes the `Request` in its constructor, and returns a `Response`. It
  coordinates; it should not contain business logic or build markup. Dynamic route parameters are on the request as
  `$this->request->params['slug']` — never re-parse the URI.
- A **Domain** holds the logic and knows nothing about HTTP. `handle()` returns a **`DomainResult`** — never an
  array. See below.
- A **Responder** renders — `view()` or `json()`, both returning a `Response`. Pass a status as `view($name, $data, 404)` rather than calling `http_response_code()`.

### Domains return a result, not an array

`Domain::handle()` used to return `array<string, mixed>`, and the Responder passed that array straight to the view,
where `extract()` turned its keys into template variables. So the array's keys *were* the view's variable names:
renaming `$tagline` in a template meant editing `Domains\Home`. That is the coupling the Responder exists to absorb,
and while it lasted the Responder did nothing but forward its argument.

A Domain now returns a `final readonly` value object under `Domains\Results\`, implementing
`TetherPHP\framework\Interfaces\DomainResult` (an empty marker — it exists so `handle()` and `Action::respond()`
have a type). The Responder translates it:

```php
// app/Domains/Results/Home.php — named for the domain
final readonly class Home implements DomainResult
{
    public function __construct(public string $name, public string $description) {}
}

// app/Responders/Home.php — the one place view variables are named
public function __invoke(HomeResult $result): Response
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
php tether make:feature <name>      # Action, Domain, Result, Responder and view
php tether make:action <name>
php tether make:domain <name>       # writes the Result too — the Domain's return type names it
php tether make:responder <name>    # writes the view too — the Responder renders it
php tether make:command <name>
```

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

`explain` resolves the URI the way a request would — lowercased, query string dropped — and names the parameters a
dynamic route would capture, so it answers "why does this 404?" without reading the matcher.

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

The base `TestCase` builds its own `Env` and `Log` rather than reading the `.env` on disk, so a test states the
settings it depends on and never writes into `storage/`. That is only possible because the Kernel is handed both.

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

Read values with `env('KEY')`, or `env('KEY', 'fallback')` for a default. A missing key with no default returns
`null` rather than throwing. `APP_DEBUG=true` turns on error display; anything else suppresses it.

`env()` is a one-line delegate to the `Env` that `public/index.php` built, and so is `logger()` to the `Log`. There
is no `Env::getInstance()`: if you need an environment somewhere the Kernel has not booted — a script of your own —
construct one and install it with `Env::use(Env::fromFile($path))`.

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
| The console binary itself (`bin/tether`)                     | `tetherphp-core`  |

If a change needs framework code, see the linked-development guide (`docs/agents/linked-core-development.md`) — do not vendor-patch
`vendor/dillonsmart/tetherphp-core`, as it is overwritten on the next install.

## Keeping this guide current

These guides are part of the source. When a change makes anything above inaccurate — a new `app/` directory and its
PSR-4 mapping, a routing behaviour change, a new generator, a different asset pipeline — update this file in the same
commit, along with `README.md` and `composer.local.json.example` if the autoload roots moved.
