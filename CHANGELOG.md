# Changelog

## Unreleased
- Added guard in generated `buildFromInput()` to reject non array/object inputs.
- New option `preservePropertyNames` to keep schema property names as-is.
- Added CLI flags corresponding to all specification options.
- Added convenient API for programmatic usage (`Schema2Class`)
- Support for non‑ASCII property names using transliteration.
- Options `noGetters` and `noSetters` to omit accessor methods.
- Options `noDescriptionsInSchema` and `singleLineSchema` for controlling stored schema.
- Multi‑line validation error messages for better readability.
- Improved enum handling and support for primitive union types.
- Automatic generation for schemas that contain a `definitions` block.
- Ensured unique property names after sanitisation.
- Numerous bug fixes and refactorings.
