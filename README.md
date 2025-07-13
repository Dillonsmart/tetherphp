# TetherPHP
## An ADR framework for PHP
TetherPHP is a lightweight, flexible framework for building web applications in PHP using the ADR (Action-Domain-Responder) architecture. It provides a simple and intuitive way to structure your application, making it easy to develop and maintain.

[Follow the development on X](https://x.com/dillon_smart)

## Requirements
- PHP 8.4
- Composer

## Installation
First, clone this repo.
```bash
git clone https://github.com/Dillonsmart/tetherphp.git
```

Then, navigate to the project directory and install the dependencies using Composer:
```bash
composer install
```

## Usage
To get started, take a copy of the `example.env` file and rename it to `.env`. Then, update the environment variables as needed.

### Remove example files
By default, a copy of TetherPHP contains the files for [tetherphp.com](https://tetherphp.com). It is recommended to remove these files before starting your project. You can do this by running the following command:

Example files can be found in the `app/*` directories, in the public directory and the contents of the `web.php` route file.