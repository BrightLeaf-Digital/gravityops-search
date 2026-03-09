# Multi-Input Field Support

Multi-input fields—such as **Name**, **Address**,
and **Checkbox** fields—contain multiple inputs within a single field.
GravityOps Search supports both displaying and searching these fields,
but the behavior differs slightly between display and search.

## Displaying Multi-Input Fields

When using the display attribute with the base field ID (e.g., {13}),
the plugin automatically detects if the field is multi-input and will:

- Fetch all of its sub-inputs (e.g., First Name, Last Name)
- Concatenate them into a single string separated by spaces

This allows for simple display of complete names or addresses without
needing to reference each sub-input.

To target a specific subfield (e.g., just First Name), use its input ID
directly, like {13.3}.

## Searching Multi-Input Fields

The correct way to search multi-input fields depends on the field type:

**Checkbox Fields**

-  Use the **base field ID** (e.g., `search="2"`), not the input ID
  (`2.2`)
-  This is the recommended method and ensures stability, especially if
  checkbox inputs are modified or dynamically generated

**Other Multi-Input Fields (e.g., Name, Address)**

-  Use the **individual input IDs** (e.g., `13.3`, `13.6`)
-  Searching by the base field ID (e.g., `13`) will not work for these
  fields

## Examples

Search by first and last name (multi-input Name field):

``` wp-block-code
[gravops_search target="2" search="13.3,13.6" display="Full Name: {13}"]
John|Smith
[/gravops_search]
```

Search for a selected checkbox value:

``` wp-block-code
[gravops_search target="5" search="2" display="{2}"] First Choice [/gravops_search]
```
