# tokenizer
Create and validate token for user data

##Installation

To add this package as a local, per-project dependency to your project, simply add a dependency on phpunit/php-token-stream to your project's composer.json file. Here is a minimal example of a composer.json file that just defines a dependency on Tokenizer:

```json
{
    "require": {
        "sincco/tokenizer": "~1.0"
    }
}
```


##Use

###Creation

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use \Sincco\Tokenizer\Tokenizer;

$userData = [ 'idUser'=>666, 'emailUser'=>'ivan.miranda@sincco.com' ];
$password = "p4$sw0rD";
$minutesExpiration = 10;
echo Tokenizer::create( $userData, $password, $minutesExpiration );
```

###Validation
```php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use \Sincco\Tokenizer\Tokenizer;

$password = "p4$sw0rD";
$valid = Tokenizer::validate( $token, $password );
```
