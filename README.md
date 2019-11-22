XF2Bridge
=========
Simple Laravel Auth Bridge Xenforo 2 package...

Fork of [swede2k/xf2bridge] from [URB/XenforoBridge](https://github.com/UnderRatedBrilliance/XenforoBridge) which is refactored to be utilized in Xenforo 2 without legacy support.

Installation
------------

Install the XenforoBridge package with Composer by adding the folowing to your composer.json file.

```json
{
    "require": {
        "hmanprod/xf2bridge": "dev-master"
    },
    "repositories": [
        { 
            "type": "vcs",
            "url": "https://github.com/hmanprod/xf2bridge"
        }
],
}
```

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
swede2k\XF2Bridge\XF2BridgeServiceProvider::class
```
Add this to your facades in app.php:
```php
'XF2Bridge' => swede2k\XF2Bridge\Facades\XF2Bridge::class,
```
Then publish the config file with
```
php artisan vendor:publish --provider=swede2k\XF2Bridge\XF2BridgeServiceProvider
```

This will add the file 'config/xf2bridge.php'. This is where you will place the needed configurations to use the Xenforo Bridge.
Within this config file you will need to supply the full directory path to your XenForo installation and the base url path like the example below

```php
return array(
    'xenforo_directory_path' => 'FULL PATH HERE', //full path to xenforo 2 forum
    'xenforo_base_url_path'  => '/', //Default '/', //auth redirect uri
    'use_xenforo_auth'       => true,
);
```

Installing Middleware
---------------------
To install Middleware you wil need to open up the app\Http\Kernel.php and the following middleware to either global middleware array
or the routeMiddleware array.

Here is an example adding to the routeMiddleware array

```php
protected $routeMiddleware = [
    'xen.auth' => 'swede2k\XF2Bridge\Middleware\XenAuthMiddleware',
    'xen.auth.admin' => 'swede2k\XF2Bridge\Middleware\XenAuthAdminMiddleware',
];
```

You can then use them in your routes like so
```php
Route::get('/example', ['middleware' => 'xen.auth',function(){
	//Do stuff
}]);
```

or you can use them in your controllers themselves

```php
class SampleController extends Controller {

    function __construct()
    {

        $this->middleware('xen.auth');
    }
    
}
```

or you can add guard in file 'config/auth.php':
```php
'xf2bridge' => [
    'driver' => 'xf2bridge',
    'provider' => 'users',
],
```
and use guard: 
```php
Auth::guard('xf2bridge')->id(); //get user id
Auth::guard('xf2bridge')->user(); //get user object
Auth::guard('xf2bridge')->check; //chech user login
etc...
```

or use alias methods:
```php
\XF2Bridge::getVisitor(); //get user object
\XF2Bridge::isLoggedIn(); //chech user login
etc...
```

For more information on Middleware development an installation check out [Laravel Docs - Middleware](http://laravel.com/docs/5.1/middleware)

Credits
-------

Special thanks to [VinceG](https://github.com/VinceG), the idea and much of my work is based on his package [xenforo-sdk](https://github.com/VinceG/xenforo-sdk) which was previously integrated within an ongoing project.
