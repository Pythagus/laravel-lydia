# Lydia payment
[Lydia](https://lydia-app.com/fr) is an online-payment solution. This package presents an implementation of this tool in 
an Object-Oriented view. This package is an overlay for Laravel. For any Lydia internal specification, please check the 
[Lydia package documentation](https://github.com/Pythagus/lydia). 

## Version
This package works since Laravel 7.x. Please refer to the next table to check whether your PHP version is compatible with this package.

|Package version|Laravel version|
|---------------|---------------|
| 1.x           | 7.x, 8.x      |

## Installation
You can install the package with [composer](https://getcomposer.org/) executing:
```bash
composer require pythagus/laravel-lydia
```

## Usage
This is the features included with this package:

### Configuration
You can execute the following command to generate the ```config/lydia.php``` config file:
```bash
php artisan vendor:publish --tag=lydia-config
```

The generated config file searchs in the ```.env``` file the following arguments:
```dotenv
LYDIA_DEBUG=false
LYDIA_DEBUG_VENDOR_TOKEN="Your-Token"
LYDIA_DEBUG_PRIVATE_TOKEN="Your-Token"
LYDIA_PRODUCTION_VENDOR_TOKEN="Your-Token"
LYDIA_PRODUCTION_PRIVATE_TOKEN="Your-Token"
```

### Lydia Facade
The ```Pythagus/Lydia/Lydia``` file overrides the basic Lydia facade to be more Laravel-friendly. If you want to change some
things in this file, just extend this file and add in your ```AppServiceProvider.register()``` method:
```php
use Pythagus\LaravelLydia\Lydia as OldLydia;

OldLydia::setInstance(new YourLydia()) ;
```

This facade uses a ```$savePaymentCallback``` attribute that should be set if you want your application fully working. This
callable takes an argument (array) that should be saved in your database. This package is provided with a default model:

#### PaymentLydia model
The ```Pythagus/LaravelLydia/Models/PaymentLydia``` model is a Lydia's data possible representation. You can extend this class 
to custom it, or don't use it at all.
The ```Pythagus/LaravelLydia/Lydia``` facade has a ```setDefaultPaymentDataCallback()``` method to add the callback from 
a given fillable model. If you specify a class name to ```setDefaultPaymentDataCallback()```, an instance of this class will
be made and fill with the data array. Please, check the [provided migration](src/Database/CreatePaymentLydiaTable.php) to
get a list of the filled data.

You can set the model in your ```AppServiceProvider``` :
```php
use Pythagus\LaravelLydia\Lydia;

public function register() {
     // Set the PaymentLydia model.
     Lydia::setDefaultPaymentDataCallback(YourPaymentLydia::class) ;
}
```

#### CreatePaymentLydiaTable migration
The package is also provided with a default migration implementing the main Lydia's data. Please, check the 
[provided migration file](src/Database/CreatePaymentLydiaTable.php).

### Error handling
This package is provided with a custom logger allowing you to have a list of the handled Lydia's exceptions. When an exception
is raised, It will be added into a log file in ```storage/logs/lydia/log-file.log```.

#### Before Laravel 8.x
In your ```App/Exceptions/Handler.php```, add the following lines:
```php
use Pythagus\LaravelLydia\Log\LydiaLog;
use Pythagus\Lydia\Contracts\LydiaException;

public function render($request, Throwable $throwable) {
     if($throwable instanceof LydiaException) {
          LydiaLog::report($throwable) ;

          return redirect()->back()->withInput()->withErrors($throwable->getMessage()) ;
     }
}
```
**Note :** this only is an example of what you can do when a ```LydiaException``` is raised. You can do whatever you want regarding
your application's expected behaviour.

#### Since Laravel 8.x
In your ```App/Exceptions/Handler.php```, add the following lines:
```php
use Pythagus\LaravelLydia\Log\LydiaLog;

public function register() {
     $this->reportable(LydiaLog::reportableClosure()) ;
}
```
You can grapically manage a ```LydiaException``` using ```$this->renderable()```. Please, check the [Laravel error handling](https://laravel.com/docs/8.x/errors).

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](LICENSE)