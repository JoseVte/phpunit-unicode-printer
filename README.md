## PHPUnit Unicode Printer

<p align="center">
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/license.svg" alt="License"></a>
</p>

### Installation

To get the last version of PHPUnit Unicode Printer, simply require the project using [Composer](https://getcomposer.org/):

```bash
composer require --dev josrom/phpunit-unicode-printer:9.*
```

Instead, you may of course manually update your require block and run composer update if you so choose:

```json
{
    "require-dev": {
        "josrom/phpunit-unicode-printer": "9.*"
    }
}
```

Modify the `phpunit.xml` to add the printer:

```xml
<phpunit ...
         colors="true"
         printerClass="PHPUnit\Printer"
         ...>
         ...
 </phpunit>
```

or

```xml
<phpunit ...
         colors="true"
         printerClass="PHPUnit\PrinterClass"
         ...>
         ...
 </phpunit>
```

or

```xml
<phpunit ...
         colors="true"
         printerClass="PHPUnit\PrinterMethod"
         ...>
         ...
 </phpunit>
```

### PHPUnit 8

For previous versions of PHPUnit use the tag [`0.4.*` or `8.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit8)

### PHPUnit 7

For previous versions of PHPUnit use the tag [`0.3.*` or `7.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit7)

### PHPUnit 6

For previous versions of PHPUnit use the tag [`0.2.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit6)

### PHPUnit <5

For previous versions of PHPUnit use the tag [`0.1.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit5)
