# Nesting Shortcodes

You can include shortcodes inside the `display` attribute using **double curly braces** (`{{ ... }}` syntax). This allows you to embed other shortcodes—like `gravitymath`, a nested `gravops_search` or any other shortcode—within the output for each entry.

## Basic Syntax

Wrap any shortcode inside double curly braces:

``` wp-block-code
[gravops_search display="Sum: {{gravitymath}}2+2{{/gravitymath}}"]
```

- Works with both self-closing and wrapped shortcodes
- Supports all shortcode attributes
- Placeholders like `{13}` will be parsed **before** the nested shortcode is run

<div class="wp-block-uagb-advanced-heading uagb-block-e90f7516">

## Placeholder Behavior

</div>

When nesting a `gravops_search` shortcode:

- The **outer** `gravops_search` processes its own placeholders in
  the `display` string first
- The **nested** `gravops_search` processes its own `display` attribute
  separately after it runs
- Use the format `gos:id` (no curly braces) inside nested shortcodes to
  refer to placeholder values
- Likewise, when referencing entry values inside formulas or shortcode
  attributes, you may need to use a custom merge tag
  format <span class="small">(see our snippet [here](https://brightleafdigital.io/code/entry/1642-gravityops-search-special-merge-tag-for-gravitymath/))</span>. Using
  standard merge tags like `{8}` or `{``gos``:8}` **will break** the
  shortcode. For example:

``` wp-block-code
{{gravitymath scope='view' id='1014' filter='filter_19=gos:21'}}~gos.8.sum~{{/gravitymath}}
```

This correctly filters by field 21 and calculates the sum of field 8
using special merge tag syntax.

``` wp-block-code
[gravops_search display="Lookup: {{gravops_search target='60' search='1' display='gos:23'}}"]
John
[/gravops_search]
```

## Best Practices & Caveats

- Don’t mix single and double quotes inside the `display` attribute—if
  the outer string uses double quotes, use single quotes inside:

``` wp-block-code
display="{{gravitymath scope='view' id='1014'}}2+2{{/gravitymath}}"
```

- Even when using the [Global Variables
  plugin](https://brightleafdigital.io/global-variables-for-gravity-math/),
  use the double curly brace syntax for your formulas if they are meant
  to run inside a gravops_search display attribute.
- Shortcodes inside the `display` string must either:

## Examples

Nested shortcode with computed math:

``` wp-block-code
[gravops_search display="Total: {{gravitymath}}~gos.8+gos.9~{{/gravitymath}}"]
```

Nested gravops_search to pull related field:

``` wp-block-code
[gravops_search target='60' search='1' sort_key='3' display="Submitted by {16} on {3}. Related: {{gravops_search target='61' search='2' display='gos:23'}}"]
John
[/gravops_search]
```

This could output something like:

*Submitted by John Smith on 2024-07-15. Related: Completed*
