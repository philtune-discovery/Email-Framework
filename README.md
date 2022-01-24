# Email Framework

This is a framework for us to build out consistent, error-free emails. Still very much a work in progress. The goal is to not have to think about HOW to build a bulletproof email.

Initially authored by [Phil Tune](mailto:phillip_tune@discovery.com) in Jan 2022.

## Installation

```bash
$ git clone https://github.com/philtune-discovery/Email-Framework.git
```

## Framework Directories and Bootstrapping

Inside `/framework` are a few class directories and bootstrapping files.

- `App` - has a handful of generic, App framework utility methods
- `DOM` - dependency that manages building DOM elements
- `HTMLEmail` - abstracts a lot of coding blocks used specifically in email development
- `Styles` - (todo) should manage options for painlessly managing and inserting styles
- `View` - nice utility class for loading view files and injecting data
- `autoloader.php` - generic class autoloader, (mostly) follows [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) standards
- `bootstrap.php` - called by `../index.php` for bootstrapping
- `console.php` - currently just has a quick "dump and die" function called `json_out()`
- `definitions.php` - sets a bunch of required defaults for the app
- `helpers.php` - utility functions; this is also a good place to load any helper files found inside the class directories; helpers are more succinct ways to access class methods

## Other Directories

- `config` - PHP files that should return a value, usually for storing data separate from application logic; use `config('path')` (without the trailing `.php`) to return the data
- `dist` - nice place to store output and static files; you can change this in `framework/definitions.php`
- `views` - using the `view('path', ['key'=>'val'])` helper will return files from here; also can be changed in `framework/definitions.php`
## Usage

On `/views/pages/home.php`:
```php
use HTMLEmail\HTMLEmail;

echo HTMLEmail::include('main');

// TODO - finish usage documentation
```

## Contributing
Pull requests are welcome. As industry best practices and techniques (as well as our own unique needs) evolve, this framework can and should be updated.
