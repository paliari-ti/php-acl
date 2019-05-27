# php-acl
Simple ACL control for PHP

# Install

```bash

composer require paliari/php-acl

```

# Usage

You can set permissions for a namespace, controller, and even a specific method

```php
$user_permissions = [
    'customers' => [
        'products' => [
            'index' => true,
            'show' => true
        ],
        'services' => true
    ],
    'admin' => true
];
$acl = new Paliari\PhpAcl\Acl($user_permissions);


// With permission
$callable = 'Customers\\Products:index';
$response = $acl->allowed(Paliari\PhpAcl\AclOperator::keys($callable));
var_export($response); // true


// Without permission
$callable = 'Customers\\Products:destroy';
$response = $acl->allowed(Paliari\PhpAcl\AclOperator::keys($callable));
var_export($response); // false
```

## Using as a middleware in Slim Framework

```php
<?php

namespace Middlewares;

use Slim\Http\Response,
    Slim\Http\Request,
    Paliari\PhpAcl\Acl,
    Paliari\PhpAcl\AclOperator;

class AclMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        // Set the "route" and "user" in a previous middleware
        
        $route = $request->getAttribute('route');
        $callable = $route->getCallable();
        $user = $request->getAttribute('user');
        $acl = new Acl($user->permissions);
        if (!$acl->allowed(AclOperator::keys($callable))) {
            return $response->withJson(['error' => 'Permission denied'], 403);
        }

        return $next($request, $response);
    }
}

``` 

## Define a white list to skip the acl check for a specific method

```php
$user_permissions = [
    'customers' => [
        'products' => [
            'show' => true
        ],
    ],
];
$acl = new Paliari\PhpAcl\Acl($user_permissions);

$callable = 'Customers\\Products:index';
\Paliari\PhpAcl\AclWhiteList::setKey($callable, true);
$response = \Paliari\PhpAcl\AclWhiteList::isSkip($callable) || $acl->allowed(Paliari\PhpAcl\AclOperator::keys($callable));
var_export($response); // true
```