# Examples

This folder contains example schema files and their corresponding generated output.

## `basic/`

A small schema with two definitions, where one references the other via `$ref`.  
The objects don't use complex advanced structures.

A few configuration options are set in the [basic-example-config.yaml](examples/basic/basic-example-config.yaml) file to make the resulting files smaller.

To create the output in the `generated` sub-folder, the following command can be used:

```sh
cmd/s2c generate:fromspec examples/basic/basic-example-config.yaml
```

## `advanced/`

The advanced example contains one top-level object without references, but it imposes more constraints on individual properties - such as enums, string-length limits, and so on—as well as alternative structures through `oneOf` and arrays of objects.

```sh
cmd/s2c generate:fromschema \
  examples/advanced/advanced-schema.yaml \
  examples/advanced/generated \
  --class User \
  --target-namespace Example\\Advanced
```

No config file is used.