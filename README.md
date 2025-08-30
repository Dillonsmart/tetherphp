<p align="center"><a href="https://tetherphp.com" target="_blank"><img src="https://tetherphp.com/Tetherphant400.png" width="200" alt="TetherPHP Logo"></a></p>
<p align="center">
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/dt/dillonsmart/tetherphp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/v/dillonsmart/tetherphp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/dillonsmart/tetherphp"><img src="https://img.shields.io/packagist/l/dillonsmart/tetherphp" alt="License"></a>
</p>

## About TetherPHP

-----------
TetherPHP is a lightweight, flexible framework for building web applications in PHP using the ADR (Action-Domain-Responder) architecture. It provides a simple and intuitive way to structure your application, making it easy to develop and maintain.

[Follow the development on X](https://x.com/dillon_smart)

## Installation
You can install TetherPHP using Composer. Run the following command in your terminal:

```bash
composer create-project dillonsmart/tetherphp ./
```

Once installed, ensure you create a `.env` file in the root of your project by copying the provided `.env.example` file.

Then, run the following command to install the necessary dependencies:

```bash
composer install
```


## Usage
To get started with TetherPHP, please refer to the [documentation](https://tetherphp.com/docs).

## Local Development
For local development of TetherPHP and the core TetherPHP framework files [tetherphp-core](https://github.com/Dillonsmart/tetherphp-core) create a composer.local.json file, and add the following code to it:

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

Then run the following command in your terminal:

```bash
COMPOSER=composer.local.json composer update
```

You can also create an alias for the command to make it easier to run:

```bash
alias composer-local='COMPOSER=composer.local.json composer update'
```