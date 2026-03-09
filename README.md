<p align="center"><a href="https://tetherphp.com" target="_blank"><img src="https://tetherphp.com/Tetherphant400.png" width="200" alt="TetherPHP Logo"></a></p>
<p align="center">
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/dt/dillonsmart/tetherphp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/v/dillonsmart/tetherphp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/l/dillonsmart/tetherphp" alt="License"></a>
</p>

## About TetherPHP

TetherPHP is a lightweight, flexible framework for building web applications in PHP using the ADR (Action-Domain-Responder) architecture. It provides a simple and intuitive way to structure your application, making it easy to develop and maintain.

[Follow the development on X](https://x.com/d2sdev)

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
│   ├── Domains/        # Business logic
│   ├── Responders/     # Response formatters
│   └── Views/          # Templates and partials
├── public/             # Web root (index.php, compiled assets)
├── resources/          # Source assets (CSS)
├── routes/             # Route definitions
├── src/                # Core framework library
│   └── framework/
│       ├── Commands/   # CLI commands
│       ├── DTOs/       # Data Transfer Objects
│       ├── Helpers/    # Utility functions
│       ├── Interfaces/ # Contracts
│       ├── Modules/    # Core modules (Env, Console, Log)
│       ├── Requests/   # Request handling
│       ├── Sessions/   # Session & CSRF management
│       └── Stubs/      # Code generation templates
└── storage/            # Logs and application storage
```

## Usage

To get started with TetherPHP, please refer to the [documentation](https://tetherphp.com/docs).

## Monorepo

This repository is a monorepo. The core framework code in `src/` is automatically split and published to [tetherphp-core](https://github.com/Dillonsmart/tetherphp-core) via GitHub Actions using `splitsh-lite`.

## Local Development

For local development of TetherPHP alongside the core framework files, create a `composer.local.json` file:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../tetherphp-core"
        }
    ],
    "require": {
        "dillonsmart/tetherphp-core": "*"
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

Then run:

```bash
COMPOSER=composer.local.json composer update
```

You can also create an alias for convenience:

```bash
alias composer-local='COMPOSER=composer.local.json composer update'
```