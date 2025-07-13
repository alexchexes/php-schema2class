# JSON Schema to PHP Class Converter

Generate PHP classes from [JSON Schema][jsonschema] automatically. Use it from PHP code or the CLI, and enjoy immutability, self‑validation, and easily update the generated classes as your schema evolves.

- [Installation](#installation)
- [Usage](#usage)
- [Options](#options)
- [Example workflow](#example-workflow)
- [Compatibility](#compatibility)
- [Features](#features)
- [Advanced programmatic usage](#advanced-programmatic-usage)

## Installation

Install with [Composer](https://getcomposer.org/):

```sh
composer require --dev helmich/schema2class
```

## Usage

You can use this tool in three different ways:

### A. Via the CLI, passing options and the schema path directly

```sh
vendor/bin/s2c generate:fromschema --class User my-schema.json src/TargetDir   # or ./my-schema.yaml

# On Windows CMD:
php vendor\bin\s2c generate:fromschema --class User my-schema.json src\TargetDir
```

See [all available options](#options).

### B. Via the CLI, using a configuration file

1. Create a configuration file, for example `my-config.yaml` (or the equivalent `.json` file):

```yml
options:
  targetPHPVersion: "7.4" # or "5.6", "8", "8.3", etc.
  noSetters: true
files:
  - input: "path/to/schema.json" # On Windows: "D:/path/to/schema.json"
    className: "MyClass" # Omit this if your schema is a list of definitions
    options:
      targetDirectory: "src/TargetDir"
      targetNamespace: "My\\Namespace"
```

2. Run the generator:

```sh
vendor/bin/s2c generate:fromspec my-config.yaml   # or my-config.json
```

You can also name the config file `.s2c.yaml` and execute the command without arguments:

```sh
vendor/bin/s2c generate:fromspec
```

`.s2c.yaml` will be used automatically.

### C. From PHP code

The simplest way to use the generator programmatically is to call `Schema2Class` class methods.

Generate from a configuration file:

```php
use Helmich\Schema2Class\Schema2Class;

$generator = new Schema2Class();
$generator->generateFromSpec('my-config.yaml');
```

Or from a configuration defined as a PHP array:

```php
$generator->generateFromSpec([
    'options' => [
        'targetPHPVersion' => '8.4',
    ],
    'files' => [
        [
            'input'     => 'path/to/schema.json', // or inline PHP assoc array
            'className' => 'MyClass',
            'options'   => [
                'targetDirectory' => 'src/TargetDir',
                'targetNamespace' => 'My\Namespace',
            ],
        ],
    ],
]);
```

Or from a schema provided as a PHP array:

```php
$schema = [
    'required'   => ['name'],
    'properties' => [
        'name' => ['type' => 'string'],
    ],
];

$generator->generateFromSchema($schema, 'MyClass', [
            'targetDirectory' => 'MyDir',
            'targetNamespace' => 'My\Namespace',
        ]);
```

See also the [advanced programmatic usage](#advanced-programmatic-usage) section.

## Options

Both the CLI and specification files accept the following options:

| CLI option                    | Config file option                 | Description                                                                                                                                                                                          |
| ----------------------------- | ---------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| _(1st argument)_              | `input`                            | JSON Schema file path (`.json`, `.yml`, or `.yaml` are supported) or an inline json schema object.
| _(2nd argument)_              | `targetDirectory`                  | Directory in which the generated files will be placed.                                                                                                                                               |
| `--clean-dir`                 | `cleanTargetDirectory`             | Remove all files from the target directory before writing new ones.                                                                                                                                  |
| `--target-namespace`          | `targetNamespace`                  | Namespace to use for every generated class (automatically determined from `composer.json` if omitted).                                                                                               |
| `--class`, `-c`               | `className`                        | Class name to generate. Use this only when generating a single class from a schema that contains one top‑level object (not from a schema with multiple `definitions`).                               |
| `--target-php`, `-p`          | `targetPHPVersion`                 | PHP version **with which** the generated code must be compatible. Numeric value without exact subversion like `5`, `7` or `8` resolves to the latest (`5.6`, `7.4`, `8.x`), not to `5.0`/`7.0`/`8.0` |
| `--disable-strict-types`      | `disableStrictTypes`               | Omit the `strict_types` declaration.                                                                                                                                                                 |
| `--treat-default-as-optional` | `treatValuesWithDefaultAsOptional` | Treat properties that have a default value as optional.                                                                                                                                              |
| `--inline-allof`              | `inlineAllofReferences`            | Inline `allOf` references before generating classes.                                                                                                                                                 |
| `--validator-expr`            | `newValidatorClassExpr`            | Expression used to create a validator instance (e.g. `new MyValidator()`).                                                                                                                           |
| `--preserve-property-names`   | `preservePropertyNames`            | Keep property names as is instead of converting them to camelCase (non-valid identifiers names will be sanitized).                                                                                   |
| `--no-getters`                | `noGetters`                        | If **true**, no getter methods are generated and all properties are `public`.                                                                                                                        |
| `--no-setters`                | `noSetters`                        | Do not generate `withX()` / `withoutX()` methods.                                                                                                                                                    |
| `--mutable-setters[=chainable]` | `mutableSetters`                   | Generate mutable `setX()` methods. Use `chainable` to return `$this`.
| `--no-schema-metadata`    | `noSchemaMetadata`           | Remove description, title and other non-validation metadata fields from the embedded schema.                                                                                                                             |
| `--single-line-schema`        | `singleLineSchema`                 | Store the validation schema on a single line in the generated class to make the `.php` file smaller.                                                                                                 |
| `--no-enums`                  | `noEnums`                          | Disable generation of PHP `enum` classes even when targeting PHP 8.1 or newer.                                                                                                                       |
| `--dry-run`                   | –                                  | Print the output to the console instead of writing files.                                                                                                                                            |

Every option except `input` and `className` can appear both at the top level of the config file and within each item in the `files` array to override the default settings.  
See the [example config file](examples/basic/basic-example-config.yaml).

## Example workflow

To demonstrate Schema2Class's capabilities and how to use the generated code, consider the following workflow:

1. You have a JSON Schema file [like this](examples/basic/basic-example.json) (can be `.json`, `.yml`, or `.yaml`).

2. You define the desired behaviour of the generator and run it:

   - with a [configuration file like this](examples/basic/basic-example-config.yaml)
     In this case, run:

   ```sh
   vendor/bin/s2c generate:fromspec basic-example-config.yaml
   ```

   - or by passing the same options directly to the CLI:

   ```sh
   vendor/bin/s2c generate:fromschema \
     examples/basic/basic-example.json \
     examples/basic/generated \
     --target-namespace Example\\Basic \
     --target-php 7.4 \
     --single-line-schema --no-schema-metadata
   ```

3. By this point you're all set: Schema2Class has automatically created the PHP classes [\[1\]](examples/basic/generated/User.php) and [\[2\]](examples/basic/generated/Address.php):

   ```text
   examples/basic/generated
      ├── Address.php
      └── User.php
   ```

   - **Note:** In a real project you can omit `--target-namespace`; Schema2Class will try to infer the target namespace from your `composer.json` file. If that fails, the last segment of the target directory is used as a fallback.
   - **Also note:** At the moment it is not possible to generate classes without a namespace.

Next, use the generated classes in your code:

```php
// App.php
<?php

$someApiResponse = '...';
$userData = json_decode($someApiResponse, true);

$user = \Example\Basic\User::buildFromInput($userData);

// or, if for some reason you don't care about validation:
$user = \Example\Basic\User::buildFromInput($userData, false);

// Access object properties via getters:
echo "User name:   " . $user->getName();
echo "User status: " . $user->getStatus();
echo "User street: " . ($user->getAddress()?->getStreet());

// Or, if you used the `--no-getters` option, access them directly:
echo "User name:   " . $user->name;
echo "User status: " . $user->status;
echo "User street: " . ($user->address?->street);

// Update `status` WITHOUT mutating the original object:
$updatedUser = $user->withStatus("customer");

// Confirm:
echo "Old status: " . $user->getStatus();      // not mutated
echo "New status: " . $updatedUser->getStatus(); // 'customer'

// Finally, convert the updated user back to a simple PHP array:
$userAsArray = $updatedUser->toArray();
```

## Compatibility

- The generated code can be made backwards‑compatible **down to PHP 5.6**. Use the `--target-php` CLI flag (or the `targetPHPVersion` option) to set the desired version.
- The generator itself requires **PHP 8.2+**.
- The tool runs on both Windows and Unix‑like systems.

## Features

### Generator

- Generate a fully‑featured class from a JSON Schema file with a single command (zero configuration).
- Hookable and extensible.

### Generated classes

The generated classes offer:

- Typing (PHP 7+ type hints plus PHPDoc) wherever possible
- When the target PHP version is 8.1 or higher, `enum` values are emitted as PHP `enum` classes.
- Use `--no-enums`/`noEnums: true` to keep the pre‑8.1 behaviour and avoid generating enum classes.
- PHPDoc descriptions derived from schema `"description"` fields.
- All PHP properties are `private` (unless `--no-getters` is used), with getter methods and explicit return type declarations (PHPDoc for PHP 5 mode).
- Namespacing: Specify the namespace for all classes with `--target-namespace` (`targetNamespace`). If omitted, the generator inspects your `composer.json` and tries to infer it from the PSR‑4 configuration. If no match is found, the generator falls back to the name of the target directory.
  Generating classes without namespaces is currently not supported.
- Class names are derived from the names in the `"definitions"` section. If input schema is a top-level object (not `"definitions"`), the class name will be infered from schema file name, unless you set it explicitly with the `--class` (`className`) option.
- Class/enum names for sub‑objects are derived from property names.
- Classes generated for array items are suffixed with "Item". See [`Example\Advanced\User::$hobbies`](examples/advanced/generated/User.php#L203).
- `oneOf`/`anyOf` alternatives are suffixed with "AlternativeX", where _X_ is an incrementing integer. See [`Example\Advanced\User::$payment`](examples/advanced/generated/User.php#L188).
- The static method `buildFromInput(array $data[, bool $validate = true])` accepts an array (for example the result of `json_decode(..., true)`), validates it against the schema, and creates the full object graph. No additional mapping is required.
  **Note:** Do not instantiate the class directly; always use `buildFromInput(...)`.
- To disable validation, pass `false` as the second argument: `buildFromInput($data, false)`. Use at your own risk.
- The method `toArray()` returns a plain array ready for `json_encode()`.
- Properties are immutable by default; use `withX()` (or `withoutX()` for optional values) to create modified copies. Pass `--mutable-setters` to generate classic `setX()` methods instead.

## Advanced programmatic usage

If you need more control, you can create a `GeneratorRequest` and pass it to `SchemaToClassFactory`:

```php
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\SchemaLoader;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Symfony\Component\Console\Output\NullOutput;

$schema  = (new SchemaLoader())->loadSchema('example.json');
$request = new GeneratorRequest(
    $schema,
    new ValidatedSpecificationFilesItem('MyApp\\TargetDir', 'User', 'src/TargetDir'),
    new SpecificationOptions()
);

// Adding a hook
$hook = new class implements ClassCreatedHook {
    public function onClassCreated(string $name, ClassGenerator $class): void
    {
        $class->addProperty('extra');
    }
};
$request = $request->withHook($hook);

$factory = new SchemaToClassFactory();
$factory->build(new \Helmich\Schema2Class\Writer\FileWriter(new NullOutput()), new NullOutput())
    ->schemaToClass($request);
```

### Hook interfaces

The generator exposes several hook interfaces that let you customize the generated code:

- `ClassCreatedHook` – called for every generated class.
- `EnumCreatedHook` – called for every generated enum.
- `FileCreatedHook` – called before each file is written.

Implement any of these interfaces and register the instance on a `GeneratorRequest` to adjust the generated output.

[jsonschema]: http://json-schema.org/
