# The Display Attribute

The `display` attribute for GravityOps Search
[](https://brightleafdigital.io/code/entry/44-gfsearch-shortcode/)
controls what is shown for each matching entry. You can use it in two
different formats:

##  1. Basic Comma-Separated Field List

You can pass a simple comma-separated list of field or property IDs,
like:

``` wp-block-code
display="13,14,15"
```

- This will output the values of fields 13, 14, and 15 for each matching
  entry.
- By default:
  - **Single field per entry** → results are separated by commas
  - **Multiple fields per entry** → fields are separated by commas;
    entries are separated by semicolons
- You can override the entry separator with the `separator` attribute
  (supports HTML).
- **Note***: The behavior of the separator applies to both the basic
  comma-separated field list and the custom*  
  *formatting with placeholders options. The separator is only applied
  when there is more than one entry returned by the*  
  *search. To configure a blank separator, enter *`__none__`*.*

## 2. Custom Display String with Placeholders

You can build a custom display using placeholders inside a string. This
gives you full control over formatting, including HTML, text, and
shortcodes.

``` wp-block-code
display="Name: {13}, Email: {14}"
```

## Placeholder Formats

You can use placeholders to insert entry values into the output:

- `{id}` – standard numeric field or entry property
  (e.g., `{13}`, `{id}`, `{form_id}`)
- `{gos:id}` – for non-numeric properties when used in **contexts where
  merge tags may be parsed**, such as GravityView custom content
  widgets, confirmations, or notifications
- `gos:id` – used only in **nested shortcodes**
- **Tip***: Use *`{id}`* for most numeric fields, and *`{gos:id}`* for
  text-based meta like *`created_by`*, *`date_created`*, etc.*

## Default Values for Placeholders

You can include a fallback/default value inside a placeholder using `;`:

``` wp-block-code
{5;No Name Found}
{gos:created_by;Current User}
```

- Only curly-brace formats (`{}`) support default values.
- Plain format (`gos:id;default`) is **not supported**.

## Placeholder Behavior Notes

- If the **first placeholder in your display string** resolves to an
  empty value, the entire result will be treated as empty and skipped
  (unless a default value is configured).
- Always match placeholders to real field or entry property IDs in your
  form.
- Avoid nesting `"` inside the `display` string if you're already using
  double quotes to wrap it — prefer single quotes inside instead.

``` wp-block-code
display="<a href='mailto:{13}'>{13}</a>"  ✅
display="<a href="{13}">"                 ❌
```

## Special Placeholders

- {num_results} or {gos:num_results} will be replaced with the total
  number of results returned.  
  Useful when using limit="all" or for showing counts like:
  "{num_results} entries found."
- To see which keys are available for use in the display or search
  attributes: `[gravops_search target="1" display="meta"]` (where target="1" is your form ID). This will return a list
  of all meta keys for the matched entry. You can customize the layout
  with the `separator` attribute.
- **Tip***: You can also find meta-keys by hovering or clicking on
  column headers in Forms → Entries in the WP admin. The meta key
  appears in the URL.*

## Nested Shortcodes

You can include shortcodes inside the `display` attribute using **double
curly braces** (`{{ ... }}` syntax). This allows you to embed other
shortcodes—like `gravitymath`, `gravops_search` or any other
shortcode—within the output for each entry. See the dedicated doc for
more info.
