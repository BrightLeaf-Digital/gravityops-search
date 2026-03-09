# General Notes

### GravityOps Search Admin Page

You can find a high-level overview of the GravityOps Search plugin by navigating to:
- **GravityOps → Search**

The **Overview** tab provides a summary of the plugin's capabilities, while the **Help** tab offers quick links to documentation, support, and community resources.

### Shortcode Tips and Guidelines

- To search multiple fields pass comma separated IDs to the search
  attribute and separate the corresponding values in
  the shortcode content with the `|` symbol. Use
  the `search_mode` attribute to configure if any or all conditions
  must
  match. To search for multiple values for the same field, repeat the
  field ID in the search attribute with the corresponding values in the
  shortcode content.
- Custom Formatting: Use the display attribute with placeholders,
  enabling displays in a complex format.
  You can create lists or tables, link to entries in a GravityView,
  create `mailto` links, the possibilities are almost
  endless! You can define CSS classes allowing for even more
  customization! See above for details.
- The search and display attributes both support entry properties and
  field IDs.
  See <a href="https://docs.gravityforms.com/entry-object/" target="_blank"
  rel="noreferrer noopener">Gravity Forms Entry Object</a>.
- To perform a global search for any field with a specified value, leave
  the corresponding search ID blank.
- To display values from a field without searching, omit the search
  attribute and shortcode content.
- **Sorting:**
  Use `sort_key` (field ID, entry property, or entry meta
  key), `sort_direction` (`ASC`, `DESC` (default), `RAND`),
  and `sort_is_num` (`true`/`false`). For secondary sorting,
  use `secondary_sort_key` and `secondary_sort_direction`.
  Secondary sorting is ignored if primary sort direction is `RAND`.
  Please note that dates are numeric regarding
  the `sort_is_num` attribute.
- Use the `unique` attribute with any non-empty value to return only
  unique results. **Please Note:** that if the results
  are not exactly the same they will be treated as unique. So the
  results example.co**m** and example.co**n** as a typo
  will be considered unique.
- To search for empty values, leave the shortcode content blank and use
  the `search_empty` attribute with any non-empty value.
- Use the `default` attribute to specify a value to display when no
  results are found or for blank values within entries.
- Use the `link` attribute with any non-empty value to wrap each result
  in a link to the entry view page in the WordPress admin.
- When using the shortcode content to pass in search values (separated
  by the \| character), avoid using the pipe (\|)
  symbol inside the actual values themselves. Escaping is not currently
  supported, so including a pipe within a value may
  result in incorrect or partial matches.
- The `[gravops_search]` shortcode does not restrict access by default.
  Anyone who can view the page can see the search results, including
  Gravity Forms entry data.
  To protect sensitive information, place the shortcode inside pages
  with appropriate access controls (e.g., membership plugins, password
  protection, or role-based visibility).
- Each `[gravops_search]` shortcode runs a live database query. Using
  many shortcodes, large forms, or limit="all" can slow down page
  loads.
  To improve speed:
  - Use limit to cap results
  - Minimize nested shortcodes
  - Consider caching the page output
- If there are more search IDs than values, extra fields will search for
  blank entries. Extra values beyond the number of IDs are ignored.
