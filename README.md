# JSONSchema to PHP class converter

Build PHP classes from [JSON schemas][jsonschema] automatically.

## Example

Consider a simple JSON schema (shown here in YAML format), stored in a file `example.yaml`:

```yaml
required:
  - givenName
  - familyName
properties:
  givenName:
    type: string
  familyName:
    type: string
  hobbies:
    type: array
    items:
      type: string
  location:
    properties:
      country:
        type: string
      city:
        type: string
```

The same schema can of course be written in JSON:

```json
{
  "required": ["givenName", "familyName"],
  "properties": {
    "givenName": {"type": "string"},
    "familyName": {"type": "string"},
    "hobbies": {"type": "array", "items": {"type": "string"}},
    "location": {
      "properties": {
        "country": {"type": "string"},
        "city": {"type": "string"}
      }
    }
  }
}
```

Using this converter, you can automatically generate PHP classes from this schema
with accessor and conversion functions:

```bash
$ vendor/bin/s2c generate:fromschema --class User ./example.yaml src/Target
# or
$ vendor/bin/s2c generate:fromschema --class User ./example.json src/Target
```

This command will automatically try to infer a PHP target namespace from your `composer.json` file and automatically create the appropriate PHP classes:

```bash
$ find src/Target
src/Target
src/Target/User.php
src/Target/UserLocation.php
```

Then, use the classes in your code:

```php
$userData = json_decode("user.json", true);
$user = \MyNamespace\Target\User::buildFromInput($userData);

echo "Hello, " . $user->getGivenName() . "\n";
```

### Schemas with definitions

If your schema contains a `definitions` section, classes are generated for every definition as well. References like `"$ref": "#/definitions/Address"` will use the generated `Address` class automatically.


## Compatibility

This tool requires PHP 8.2 or newer to run.

The generated code can be backwards-compatible up until PHP 5.6. Use the `--target-php` flag to set the desired PHP version that the generated code should be compatible with. When [using a configuration file](#using-configuration-files), use the `targetPHPVersion` property. 

## Creation result

The generated classes have these features:

- The class namespace can either be specified via command-line (`--target-namespace`), specification file (`targetNamespace`). If neither is specified, the generator will inspect the `composer.json` of your project, look for any PSR-4 configuration and infer the namespace from there.
- The main object's name is defined by the command-line (`--class`) or the specification file.
- Sub-object's names are taken from the property name.
- Array items are suffixed 'Item'.
- `OneOf` alternatives are suffixed 'AlternativeX', with `X` being an incremented integer.
- The constructor has arguments for all required properties in the schema.
- All properties are private, with getter methods for access, and explicit type declarations for the return value (in PHP5 mode, only PHPDoc is used).
- Static function `buildFromInput(array $data)` accepts an array (using `json_decode('{}', true)`), validates it according to the schema and creates the full object tree as return value. An additional mapping step is not required.
- Function `toJson()` returns a plain array ready for `json_encode()`.
- Writing to any object's properties is done immutably by using `withX()` (or `withoutX()` for optional values). This will return a new instance of that object with the value changed.

As an example, a shortened version with all comments removed, from the above schema shows the location, only containing the city (country is behaving the same, but with a different name)

```php
class UserLocation
{
    private static array $schema = array(
        'properties' => array(
            'city' => array(
                'type' => 'string',
            ),
        ),
    );

    private ?string $country = null;

    private ?string $city = null;

    public function __construct()
    {
    }

    public function getCity() : ?string
    {
        return $this->city;
    }

    public function withCity(string $city) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($city, static::$schema['properties']['city']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->city = $city;

        return $clone;
    }

    public function withoutCity() : self
    {
        $clone = clone $this;
        unset($clone->city);

        return $clone;
    }

    public static function buildFromInput(array $input) : UserLocation
    {
        static::validateInput($input);

        $city = null;
        if (isset($input['city'])) {
            $city = $input['city'];
        }

        $obj = new static();
        $obj->city = $city;
        return $obj;
    }

    public function toJson() : array
    {
        $output = [];
        if (isset($this->city)) {
            $output['city'] = $this->city;
        }

        return $output;
    }

    public static function validateInput(array $input, bool $return = false) : bool
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($input, static::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return $e["property"] . ": " . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(", ", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
    }
}
```

## Installation

Install using Composer:

```bash
$ composer require --dev helmich/schema2class
```

## Using configuration files

In many projects, you're going to want to keep an evolving JSON schema in sync with the generated PHP classes continuously. For this reason, S2C allows you to create a configuration file `.s2c.yaml` that stores the most common conversion options:

```yaml
targetPHPVersion: "7.4"
files:
- input: src/Spec/Spec.yaml
  className: Specification
  targetDirectory: src/Spec
```

You can store your local configuration in this yaml file and start the generation process by calling

```bash
s2c generate:fromspec
```

This will scan for `.s2c.yaml` in the current directory and use it's parameters. If you need to have different files for multiple schemas, you can provide a config file as a parameter.

### Options

The specification `options` section (or the equivalent CLI flags) allows fine tuning of the generated classes.  Important options include:

- `disableStrictTypes` – omit the `strict_types` declaration.
- `treatValuesWithDefaultAsOptional` – treat properties with a default value as optional.
- `inlineAllofReferences` – inline `allOf` references before generating classes.
- `newValidatorClassExpr` – expression used to create a validator instance.
- `preservePropertyNames` – keep property names as‐is instead of converting them to camelCase.
- `noGetters` – generate public properties only, without getter methods.
- `noSetters` – omit `withX()`/`withoutX()` methods.
- `noDescriptionsInSchema` – drop `description` fields from the embedded schema.
- `singleLineSchema` – store the validation schema as a single line in the generated class.

See [`src/Spec/Spec.yaml`](src/Spec/Spec.yaml) for a full list of available options and their defaults.

### Programmatic usage

The generator can be invoked from PHP code without the CLI.  Create a `GeneratorRequest` and pass it to `SchemaToClassFactory`:

```php
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\SchemaLoader;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Symfony\Component\Console\Output\NullOutput;

$schema = (new SchemaLoader())->loadSchema('example.json');
$request = new GeneratorRequest(
    $schema,
    new ValidatedSpecificationFilesItem('My\\Target', 'User', 'src/Target'),
    new SpecificationOptions()
);
$factory = new SchemaToClassFactory();
$factory->build(new \Helmich\Schema2Class\Writer\FileWriter(new NullOutput()), new NullOutput())
    ->schemaToClass($request);
```

[jsonschema]: http://json-schema.org/
