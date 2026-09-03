<p align="center"><a href="https://tetherphp.com" target="_blank"><img src="https://tetherphp.com/Tetherphant400.png" width="200" alt="TetherPHP Logo"></a></p>
<p align="center">
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/dt/dillonsmart/tetherphp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/v/dillonsmart/tetherphp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/l/dillonsmart/tetherphp" alt="License"></a>
</p>

## About TetherPHP

TetherPHP is a lightweight, flexible framework for building web applications in PHP using the ADR (Action-Domain-Responder) architecture. It provides a simple and intuitive way to structure your application, making it easy to develop and maintain.

[Follow the development on X](https://x.com/DS01Builds)

## Features

- **ADR Architecture** — Clean separation of concerns using the Action-Domain-Responder pattern
- **Routing** — Simple routing with support for dynamic parameters and route grouping
- **Session & CSRF Protection** — Built-in session management and CSRF token validation
- **Environment Configuration** — `.env` file support for managing application settings
- **CLI Tools** — Code generation commands for scaffolding Actions, Domains, and Responders
- **Tailwind CSS** — Pre-configured with Tailwind CSS for styling
- **Logging** — Built-in logging to the `storage/` directory

## Requirements

- PHP 8.4 or higher
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
│   ├── Actions/        # Request handlers
│   ├── Commands/       # Your console commands (created by make:command)
│   ├── Domains/        # Business logic
│   ├── Responders/     # Response formatters
│   └── Views/          # Templates and partials
├── public/             # Web root (index.php, compiled assets)
├── resources/          # Source assets (CSS)
├── routes/             # Route definitions
└── storage/            # Logs and application storage
```

The framework itself is not part of this repository. It is installed as the
[`dillonsmart/tetherphp-core`](https://github.com/Dillonsmart/tetherphp-core) Composer package and lives in
`vendor/dillonsmart/tetherphp-core`.

## Usage

To get started with TetherPHP, please refer to the [documentation](https://tetherphp.com/docs).

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
