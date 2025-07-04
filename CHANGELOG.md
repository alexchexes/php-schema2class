# Changelog

## v4.0.0

This version introduces a substantial update across the project with new features, bug fixes, and refactoring. Key highlights:

### Programmatic API

- A new `Schema2Class` provides a direct PHP interface for generating classes without the CLI. It supports generating from specification files, specification arrays, or directly from a schema passed as an array. Example usage is documented in the README.

### New options (extended specification)

New options provide more control over how classes are generated:

- `preservePropertyNames` - keep generated PHP property names as-is instead of converting them to camelCase
- `noGetters` - if _true_, no getter methods will be generated, and all properties will be `public`.
- `noSetters` - don't generate `withX()`/`withoutX()` methods.
- `noSchemaMetadata` - drop description, title and other non-validation metadata fields from the embedded schema.
- `singleLineSchema` - store the validation schema as a single line in the generated class to reduce the length of .php files.
- `noEnums` - Disable generation of PHP `enum` classes even when targeting PHP 8.1 or newer.
- `cleanTargetDirectory` - remove all files from the target directory before generation.

### Additional CLI options

- CLI now supports all the options previously available only via spec files, e.g., `--disable-strict-types`, `--inline-allof`, `--treat-default-as-optional`, etc., and all the new options like `--no-getters`, `--no-setters`, `--preserve-property-names`, etc.

### Generator enhancements

Now it:

- Automatically creates classes for schemas with a `definitions` block (no more `cannot generate class for types other than 'object'`).
- Supports non-ASCII property names. Any identifier is sanitized (with a uniqueness check), including when used with the `preservePropertyNames` option.
- Improves camelCase/PascalCase handling when generating identifier names.
- Omits the default `null` value for properties listed in the `required` schema block.
- Improves type hints and PHPDoc type generation for complex types like unions, nested arrays, etc.
- Adds a guard against passing anything other than an array/object to `buildFromInput`, building multi-line validation error messages.
- Setter methods (`withX()`) now accept an optional `$validate` argument to be able skip validation, mirroring `buildFromInput()`.
- Skips emitting empty `__construct` or `__clone` methods.

### Better support for older PHP versions:

- Added proper handling for **enums** when the target PHP version is less than 8.1.

### String utilities

- `StringUtils` adds transliteration via `voku/portable-ascii`, robust identifier sanitization, and improved camelCase/PascalCase handling.

### Enums

- Laminas's immutable EnumGenerator replaced with own mutable implementation PhpParserEnumGenerator built on nikic/php-parser which allows modifications via the hook system.

### Documentation

- README expanded with examples for JSON schema input, specification files, and programmatic API usage.

### Internal

- Several new classes and interfaces to handle new functionality; extensive refactoring of existing classes.
- **Updated tests and fixtures**: Many new test cases covering new functionality such as typed arrays, non-ASCII identifiers, and the programmatic API.
- **Dependency updates**:
  - New dependencies: `voku/portable-ascii`, `nikic/php-parser`
  - Bumped PHPUnit to 12 and Psalm to 6

### Breaking changes?

- Configuration layout has been simplified: there are now only two top-level keys, `options` and `files`. Each `files` item has three keys: `input`, an optional `className`, and `options`, where the `options` object can override any setting from the top-level `options`.

- `files[].input` now accepts inline schema arrays in addition to file paths.

In other aspects, the main public API (spec-files and CLI options handling) has only been extended, not changed, so existing functionality in a simple case should continue to work as expected (after adjusting spec-file layout if you use it).

However, the generated class code may differ in some aspects due to improved generation logic, so it's recommended to review any changes carefully.

Additionally, the internals have changed drastically, so if you run custom hooks or other complex extensions, you may need some adjustements.
