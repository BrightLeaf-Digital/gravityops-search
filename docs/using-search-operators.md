# Using Search Operators

The `operators` [attribute](https://brightleafdigital.io/code/entry/44-gfsearch-shortcode/) allows you to define **how each search value is compared** to its corresponding field in the `search` attribute. It should be a **comma-separated list**, with each operator matching its position to the same-positioned field ID in the `search` attribute.

## Supported Operators

<figure class="wp-block-table">
<table class="has-fixed-layout">
<thead>
<tr class="header">
<th>Operator</th>
<th>Meaning</th>
</tr>
</thead>
<tbody>
<tr class="odd">
<td><code>=</code> or <code>is</code></td>
<td>Equals</td>
</tr>
<tr class="even">
<td><code>!=</code>, <code>isnot</code>, <code>is not</code></td>
<td>Not equal to</td>
</tr>
<tr class="odd">
<td><code>contains</code></td>
<td>Partial match</td>
</tr>
<tr class="even">
<td><code>like</code></td>
<td>SQL-style <code>LIKE</code> with custom wildcards</td>
</tr>
<tr class="odd">
<td><code>in</code></td>
<td>Value is in array</td>
</tr>
<tr class="even">
<td><code>notin</code>, <code>not in</code></td>
<td>Value is NOT in array</td>
</tr>
<tr class="odd">
<td><code>gt</code></td>
<td>Greater than</td>
</tr>
<tr class="even">
<td><code>lt</code></td>
<td>Less than</td>
</tr>
<tr class="odd">
<td><code>gt=</code></td>
<td>Greater than or equal to</td>
</tr>
<tr class="even">
<td><code>lt=</code></td>
<td>Less than or equal to</td>
</tr>
</tbody>
</table>
</figure>

> *To compare against multiple values using *`in`* or *`not in`*, pass a PHP-style array in the shortcode content, like:
>
> ``` wp-block-code
> array('item one', 'item two', 'item three')
> ```

## Operator Matching Behavior

Each operator in `operators` must match the position of a field in the `search` attribute:

- If you pass **fewer operators** than `search` fields:
  - The **remaining fields default to** `=` (exact match).
  - This lets you apply advanced filters only where needed.

- If you pass **more operators** than `search` fields:
  - **Extra operators are ignored.**

- If `operators` is omitted entirely:
  - **All search fields use `=` by default.**

## Examples

Basic match with mixed operators

``` wp-block-code
[gravops_search target="1" search="3,5,8" operators="contains,=,gt"]
Smith|john@example.com|50
[/gravops_search]
```

- Field 3 must *contain* "Smith"
- Field 5 must *equal* "<john@example.com>"
- Field 8 must be *greater than* 50

Using array for `in`

``` wp-block-code
[gravops_search search="5" operators="in"]
array('yes','maybe')
[/gravops_search]
```

- Field 5 must match one of the given values

Mixing defaults and explicit operators

``` wp-block-code
[gravops_search search="3,5,8" operators="contains"]
Smith|john@example.com|50
[/gravops_search]
```

- Field 3 uses `contains`
- Field 5 and 8 default to `=`

## Tips & Gotchas

- **Array format:** Use `array('one','two')` exactly—do not just write comma-separated values.
- **Order matters:** Ensure your `operators` match the order of `search` fields.
- If a field is repeated in `search`, you can still assign distinct operators per instance.
