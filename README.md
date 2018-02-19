## PHPUnit Unicode Printer

<p align="center">
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/license.svg" alt="License"></a>
</p>

### Installation

To get the last version of ChannelLog, simply require the project using [Composer](https://getcomposer.org/):

```bash
composer require josrom/phpunit-unicode-printer:0.1.*
```

Instead, you may of course manually update your require block and run composer update if you so choose:

```json
{
    "require": {
        "josrom/phpunit-unicode-printer": "0.1.*"
    }
}
```

Modify the `phpunit.xml` to add the printer:

```xml
<phpunit ...
         colors="true"
         printerClass="PhpUnit\Printer"
         ...>
         ...
 </phpunit>
```