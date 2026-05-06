## PHPUnit Unicode Printer

<p align="center">
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/josrom/phpunit-unicode-printer"><img src="https://poser.pugx.org/josrom/phpunit-unicode-printer/license.svg" alt="License"></a>
</p>

### Requirements

- PHP 8.3+
- PHPUnit 12.x

### Installation

Require the package with [Composer](https://getcomposer.org/):

```bash
composer require --dev josrom/phpunit-unicode-printer:12.*
```

Or update your `require-dev` block manually and run `composer update`:

```json
{
    "require-dev": {
        "josrom/phpunit-unicode-printer": "12.*"
    }
}
```

### Configuration (PHPUnit 10 / 11 / 12)

PHPUnit 10 removed the `printerClass` XML attribute and replaced the printer
architecture with an event-driven extension system, which PHPUnit 11 and 12
keep. Register the printer as a `<bootstrap>` extension in your `phpunit.xml`:

```xml
<phpunit ...
         colors="true"
         ...>
    <extensions>
        <bootstrap class="PHPUnit\Extension\UnicodePrinterMethod"/>
    </extensions>
    ...
</phpunit>
```

Pick **one** of the three available extensions depending on the level of detail
you want:

| Extension class                                | Output                                                       |
|------------------------------------------------|--------------------------------------------------------------|
| `PHPUnit\Extension\UnicodePrinter`             | One progress symbol per test, grouped by test directory.     |
| `PHPUnit\Extension\UnicodePrinterClass`        | One progress symbol per test, grouped by directory + class.  |
| `PHPUnit\Extension\UnicodePrinterMethod`       | One full row per test (`(N/M) ✓ Class: Method (Xms)`).       |

#### Status symbols

| Status      | Symbol | Color    |
|-------------|--------|----------|
| Passed      | ✓      | green    |
| Failed      | ✗      | red      |
| Errored     | ✗      | magenta  |
| Warning     | ✗      | yellow   |
| Risky       | ✓      | yellow   |
| Deprecation | ⚠      | yellow   |
| Notice      | ℹ      | cyan     |
| Incomplete  | ✓      | blue     |
| Skipped     | ✗      | blue     |

To make a test be flagged as **Risky**, set
`beStrictAboutTestsThatDoNotTestAnything="true"` on `<phpunit>` and write a
test method without any assertion.

#### Disable PHPUnit's default progress output

PHPUnit 10/11/12 always renders its own dotted progress (`.IIFWES…`). To avoid two
progress streams interleaving, run PHPUnit with the `--no-progress` flag:

```bash
./vendor/bin/phpunit --no-progress
```

A convenient way is to alias it in your `composer.json` scripts section:

```json
{
    "scripts": {
        "test": "phpunit --no-progress"
    }
}
```

> Note: `--no-progress` is a CLI-only option; there is no equivalent attribute
> on the `<phpunit>` element.

### Migrating from older versions

The old `printerClass="..."` attribute was removed by PHPUnit 10. Replace any
of these:

```xml
<phpunit printerClass="PHPUnit\Printer" ...>
<phpunit printerClass="PHPUnit\PrinterClass" ...>
<phpunit printerClass="PHPUnit\PrinterMethod" ...>
```

with the corresponding `<extensions>` block shown above. The original three
class names map to the new ones as:

| Before (PHPUnit ≤ 9) | After (PHPUnit 10 / 11 / 12)           |
|----------------------|-----------------------------------------|
| `PHPUnit\Printer`        | `PHPUnit\Extension\UnicodePrinter`        |
| `PHPUnit\PrinterClass`   | `PHPUnit\Extension\UnicodePrinterClass`   |
| `PHPUnit\PrinterMethod`  | `PHPUnit\Extension\UnicodePrinterMethod`  |

### Older PHPUnit versions

For previous versions of PHPUnit use these tags:

| PHPUnit  | Tag                                                                                          |
|----------|----------------------------------------------------------------------------------------------|
| 11       | [`11.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit11)                  |
| 10       | [`10.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit10)                  |
| 9        | [`9.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit9)                    |
| 8        | [`0.4.*` or `8.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit8)         |
| 7        | [`0.3.*` or `7.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit7)         |
| 6        | [`0.2.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit6)                  |
| <5       | [`0.1.*`](https://github.com/JoseVte/phpunit-unicode-printer/tree/phpunit5)                  |
