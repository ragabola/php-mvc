## Introduction

It's just a basic mvc framework for php, with a basic routing system , query builder and other cool stuffs.

## Installation

1. Clone the repository
2. Run `composer install`
3. Run `npm install`
3. Create a database and provide the credentials in the `.env` file
4. Start the app server , php -S localhost:8000 -t public

## What's included

- Routes , add routes in the `routes/web.php` file
- Controllers , add controllers in the `app/Http/Controllers` directory
- Middlewares , add middlewares in the `app/Http/Middlewares` directory
- Models , add models in the `app/Models` directory, models shipped with a basic query builder to perform basic crud operations
- Resources , add resources in the `resources` directory, includes views, assets
- Configurations , add configurations in the `config` directory
- Helpers , start using helpers in the `setup/helpers.php` file

## Usage

- Add routes in the `routes/web.php` file
```php
use App\Http\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);
```

- Add models in the `app/Models` directory
```php
namespace App\Models;

<?php

namespace App\Models;

class User extends Model
{
    // remove this if your table name is the plural of the class name
    protected string $table = 'customers';
}
```

- Add controllers in the `app/Http/Controllers` directory
```php

namespace App\Http\Controllers;

use App\Models\User;
use Setup\Transport\Request;

class HomeController
{
    public function index()
    {
        $users = User::select('*')->get();
        return view('users.index', compact('users'));
    }

    public function show(Request $request, $username)
    {
        $user = User::select('*')->where('username', $username)->firstOrFail();
        
        return view('users.show', compact('user'));
    }

    public function edit(Request $request, $username)
    {
        $user = User::select('*')->where('username', $username)->first();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $username)
    {
        User::update([
            // your data goes here
        ])->where('name', $username);

        return navigateTo('/users');
    }
}
```

- Add middlewares in the `app/Http/Middlewares` directory
```php
namespace App\Http\Middlewares;

use Setup\Transport\Request;

class Authenticate extends ChainChecker
{
    public function handle(Request $request)
    {
        if(! $request->user()) abort(401);

        $this->next($request);
    }
}
```

- Register middlewares in the `App/Http/Kernel.php` file
```php
<?php

namespace App\Http;

use App\Http\Middlewares\VerifyCsrfToken;
use App\Http\Middlewares\Authenticate;
use App\Http\Middlewares\RedirectIfAuthenticated;
use Setup\Core\HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        // your global middlewares goes here
        VerifyCsrfToken::class,
    ];

    protected $middlewareAliases = [
        // your route specific middlewares goes here
        'auth' => Authenticate::class,
        'guest' => RedirectIfAuthenticated::class
    ];
}
```
- Then use the middlewares in the routes
```php
use App\Http\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index'])->middleware('auth');
```

- Access the configuration in the `config` directory using `config('config_key')`

```php
config('database.driver');
```
