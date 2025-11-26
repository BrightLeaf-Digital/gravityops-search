<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GravityOps_Search class
 *
 * Main class for the GravityOps_Search plugin that extends GFAddOn
 */
class GravityOps_Search extends GFAddOn {

    // phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
    /**
     * Holds the singleton instance of the class.
     *
     * @var GravityOps_Search $_instance If available, contains an instance of this class.
     */
    private static $_instance = null;

    /**
     * The current version of the plugin
     *
     * @var string Version of the plugin.
     */
    protected $_version = '1.0.1';

    /**
     * A string representing the slug used for the plugin.
     *
     * @var string Plugin slug.
     */
    protected $_slug = 'gravityops_search';

    /**
     * The full file path of the current script.
     *
     * @var string Full path to this class file.
     */
    protected $_full_path = __FILE__;

    /**
     * The full title of the plugin
     *
     * @var string Title of the plugin to be used in UI.
     */
    protected $_title = 'GravityOps Search';

    /**
     * The short title of the plugin.
     *
     * @var string Short version of the plugin title to be used in menus.
     */
    protected $_short_title = 'GravityOps Search';
    // phpcs:enable PSR2.Classes.PropertyDeclaration.Underscore

    /**
     * Get instance of this class.
     *
     * @return GravityOps_Search
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Handles hooks and loading of language files.
     */
    public function init() {
        parent::init();
        add_shortcode( 'gravops_search', [ $this, 'gravops_search' ] );
    }

	/**
	 * Initializes the admin functionalities for the application.
	 *
	 * Sets up the necessary hooks and actions to configure the admin area, including adding the top-level menu.
	 *
	 * @return void This method does not return any value.
	 */
	public function init_admin() {
		parent::init_admin();
		add_action( 'admin_menu', [ $this, 'add_top_level_menu' ] );
	}

	/**
	 * Add a top-level menu in the WordPress admin.
	 *
	 * @return void
	 */
	public function add_top_level_menu() {
		global $menu;

		$has_full_access = current_user_can( 'gform_full_access' );
		$min_cap         = GFCommon::current_user_can_which( $this->_capabilities_app_menu );
		if ( empty( $min_cap ) ) {
			$min_cap = 'gform_full_access';
		}

		// if another plugin in our suit is already installed and created the submenu we don't have to.
		if ( in_array( 'gravity_ops', array_column( $menu, 2 ), true ) ) {
			add_submenu_page(
				'gravity_ops',
				$this->_short_title,
				$this->_short_title,
				$has_full_access ? 'gform_full_access' : $min_cap,
				$this->_slug,
				[ $this, 'create_sub_menu' ]
			);

			return;
		}

		$number        = 10;
		$menu_position = '16.' . $number;
		while ( isset( $menu[ $menu_position ] ) ) {
			$number       += 10;
			$menu_position = '16.' . $number;
		}

		$this->app_hook_suffix = add_menu_page(
			'GravityOps',
			'GravityOps',
			$has_full_access ? 'gform_full_access' : $min_cap,
			'gravity_ops',
			[ $this, 'create_top_level_menu' ],
			$this->get_app_menu_icon(),
			$menu_position
		);
		add_submenu_page(
			'gravity_ops',
			$this->_short_title,
			$this->_short_title,
			$has_full_access ? 'gform_full_access' : $min_cap,
			$this->_slug,
			[
				$this,
				'create_sub_menu',
			]
		);
	}

	/**
	 * Retrieves the SVG icon for the application menu in a base64-encoded string.
	 *
	 * The method generates an SVG icon XML, encodes it in base64, and formats it as a data URL
	 * suitable for use as an image source in web applications.
	 *
	 * @return string The base64-encoded SVG icon as a data URL.
	 */
	public function get_app_menu_icon() {
		$svg_xml = '<?xml version="1.0" encoding="utf-8"?><svg height="24" id="Layer_1" viewBox="0 0 300 300" width="24" xmlns="http://www.w3.org/2000/svg" >
<defs>
<style>
      .cls-1 {
        fill: #fff;
      }
      .cls-4 {
        fill: #fff;
      }
    </style>
<radialGradient cx="-28.79" cy="-50.67" fx="-28.79" fy="-50.67" gradientTransform="translate(.26 .38) scale(1.05)" gradientUnits="userSpaceOnUse" id="radial-gradient" r="433.22">
<stop offset="0" stop-color="#402a56"/>
<stop offset="1" stop-color="#2f2e41"/>
</radialGradient>
</defs>
<g>
<g>
<path class="cls-4" d="M204.44,45.16c-7.84,2.35-15.26,5.96-22.05,10.2,0,0-.02,0-.03.01-15.43,9.64-27.63,22.58-34.25,31.59-9.53,13-27.14,30.42-43.32,13.65-2.65-2.75-4.19-6.14-4.72-9.87-1.88-13.02,8.47-30.17,26.39-38.44,33.79-15.6,95.3-12.35,77.98-7.15Z" fill="black"/>
<path class="cls-1" d="M214.25,50.81c-4.41,2.77-11.39,11-16.43,17.33,0,0,0,0-.01,0-1.67,2.09-3.13,3.98-4.21,5.39-11.02,14.34-31.85,47.1-37.9,60.65-8.26,18.49-36.2,49.52-61.36,35.86-.16-.08-.32-.18-.47-.27-.04-.02-.08-.05-.12-.06-25.34-14.5-19.28-50.67,2.72-74.12-8.81,13.47-6.66,25.45.75,32.32,17.55,16.25,36.77,2.62,47.34-13.87,8.15-12.72,17.71-24.76,28.14-34.82,8.38-8.08,23.51-19.35,32.73-24.2,3.09-1.64,7.15-3.25,8.83-4.2Z" fill="black"/>
<path class="cls-1" d="M221.42,60.81c-.66,1.3-5.48,10.14-10.42,20.46t0,.01c-3.67,7.67-7.41,16.16-9.58,23-4.32,13.6-16.91,56.93-19.49,64.57-4.83,14.29-11.87,24.53-20.51,31.19-.29.23-.58.44-.88.66-9.4,6.88-20.63,9.65-32.99,8.88-15.67-.98-27.53-10.99-31.65-27.29,2.63,5.35,7.76,9.4,16.05,10.18,17.18,1.61,29.48-5.6,37.79-13.93,2.9-2.9,5.31-5.95,7.27-8.81,7.58-11.05,20.74-47.79,28.81-63.68,15.38-30.3,27.18-36.6,35.61-45.22Z" fill="black"/>
<path class="cls-1" d="M223.33,174.26h0c-.01.29-.03.58-.05.87-1.12,21.48-14.24,36.62-31.35,38.34-12.52,1.25-24.18-3-31.41-12.78.29-.21.58-.43.88-.66,3.05,1.98,6.75,3.07,11.19,3.03,22.82-.2,31.59-25.49,32.65-44.19,3.54-62.38,17.03-82.68,18.03-85.08-.29,4.36-4.98,17.58-5.62,30.49-.18,3.55-.23,7-.19,10.35h0c.27,21.03,4.28,38.11,5.6,51.39.28,2.83.36,5.58.27,8.23Z" fill="black"/>
<path class="cls-1" d="M241.9,175.78c-7.01,2.69-13.2,2.1-18.62-.65.02-.29.03-.58.05-.86,2.51.46,5.02.16,7.53-.96,11.48-5.11,7.91-25.36,3.03-36.08-4.65-10.23-7.63-25.56-8.77-44.1,5.25,23.34,16.89,31.95,23.93,41.17,6.73,8.81,16.03,32.6-7.15,41.48Z" fill="black"/>
</g>
</g>
</svg>';
		return sprintf( 'data:image/svg+xml;base64,%s', base64_encode( $svg_xml ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * Outputs the HTML for the top-level menu that showcases a list of additional plugins.
	 *
	 * @return void
	 */
	public function create_top_level_menu() {
		?>
		<h1 style="padding: 15px;">Check out the rest of our plugins</h1>
		<ul style="padding-left: 15px; font-size: larger; line-height: 1.5em; list-style: disc;">
			<li>
				<a target="_blank" href="https://brightleafdigital.io/asana-gravity-forms/">Asana Integration for Gravity Forms</a>
			</li>
			<li>
				<a target="_blank" href="https://brightleafdigital.io/mass-email-notifications-for-gravity-forms/">Mass Email Notifications for Gravity Forms</a>
			</li>
			<li>
				<a target="_blank" href="https://brightleafdigital.io/turn-gravityview-into-a-kanban-project-board/">Kanban View for Gravity View</a>
			</li>
			<li>
				<a target="_blank" href="https://brightleafdigital.io/recurring-form-submissions-for-gravity-forms/">Recurring Form Submissions for Gravity Forms</a>
			</li>
			<li>
				<a target="_blank" href="https://brightleafdigital.io/global-variables-for-gravity-math/">Global Variables for Gravity Math</a>
			</li>
			<li>
				<a target="_blank" href="https://brightleafdigital.io/folders-4-gravity/">Folders 4 Gravity</a>
			</li>
			<li>
				<a target="_blank" href="https://brightleafdigital.io/gravityops-search/">GravityOps Search</a>
			</li>
			<li>
				<a target="_blank" href="https://wordpress.org/plugins/brightleaf-digital-php-compatibility-scanner/">BLD PHP Compatibility Scanner</a>
			</li>
		</ul>
		<?php
	}

	/**
	 * Creates a submenu for the plugin in the WordPress admin dashboard.
	 */
	public function create_sub_menu() {
		echo '<h1 style="padding-left: 15px;">GravityOps Search is your infinitely customizable VLOOKUP for Gravity Forms!</h1>
		<p style="padding-left: 15px; font-size: large">For more information and plugin documentation, visit our <a href="https://brightleafdigital.io/gravityops-search/" target="_blank">plugin page</a>.</p>';
	}

	/**
     * Processes the gravops_search shortcode to perform searching and displaying Gravity Forms entries
     * based on specified criteria and attributes.
     *
     * @param array  $atts An associative array of attributes, or default values.
     * @param string $content Content of the shortcode, typically search values separated by '|'.
     *
     * @return string|false Formatted search results or false if search fails due to missing attributes or invalid setup.
     */
	public function gravops_search( $atts, $content = null ) {
        $result = apply_filters( 'gogv_shortcode_process', $content );
        if ( $result !== $content ) {
            return $result;
        }

        $atts = shortcode_atts(
            [
                'target'                   => '0',
                'search'                   => '',
                'operators'                => '',
                'display'                  => '',
                'sort_key'                 => 'id',
                'sort_direction'           => 'DESC',
                'sort_is_num'              => true,
                'secondary_sort_key'       => '',
                'secondary_sort_direction' => 'DESC',
                'unique'                   => false,
                'limit'                    => '1',
                'search_mode'              => 'all',
                'separator'                => '',
                'search_empty'             => false,
                'default'                  => '',
                'link'                     => false,
            ],
            $atts,
            'gravops_search'
        );

        // Allow everything wp_kses_post allows plus <a> and its attributes
        $allowed_tags      = wp_kses_allowed_html( 'post' );
        $a_tags            = [
            'href'   => true,
            'title'  => true,
            'target' => true,
            'rel'    => true,
            'class'  => true,
            'id'     => true,
            'style'  => true,
        ];
        $allowed_tags['a'] = $a_tags + ( $allowed_tags['a'] ?? [] );

        $content = html_entity_decode( $content, ENT_QUOTES );

        $form_id = array_map( 'intval', explode( ',', $atts['target'] ) );

        $search_criteria                          = [];
        $search_criteria['status']                = 'active';
        $search_criteria['field_filters']         = [];
        $search_criteria['field_filters']['mode'] = in_array( strtolower( $atts['search_mode'] ), [ 'all', 'any' ], true ) ? strtolower( $atts['search_mode'] ) : 'all';

        if ( ! empty( $atts['search'] ) && empty( $atts['display'] ) && ! $atts['search_empty'] ) {
            return '';
        }

        $search_ids = array_map( fn( $search_id ) => GFCommon::replace_variables( $search_id, [], [] ), explode( ',', $atts['search'] ) );
        $search_ids = array_map( 'trim', $search_ids );

        // Parse operators if provided
        $operators = [];
        if ( ! empty( $atts['operators'] ) ) {
            $operators = array_map( 'trim', explode( ',', $atts['operators'] ) );
        }

        $content_values = array_map( 'trim', explode( '|', $content ) );

        foreach ( $search_ids as $index => $search_id ) {
            if ( empty( $search_id ) ) {
                continue;
            }
            $current_field = GFAPI::get_field( $form_id[0], $search_id );
            if ( $current_field && 'number' === $current_field['type'] ) {
                $content_values[ $index ] = str_replace( ',', '', $content_values[ $index ] );
            }

            // Add operator if provided for this field
            if ( ! empty( $operators[ $index ] ) ) {
                /*
                 * Validate operator against supported operators
                 * is, = (exact match)
                 * isnot, isnot, != (not equal) (<> not supported due to sanitizing issues)
                 * contains (Substring search-converted to LIKE %value%)
                 * like: SQL like with wildcards
                 * notin, not in (values not in array)
                 * in (values in array)
                 * lt, gt, lt=, gt=, (numeric operators)
                 */
                $supported_operators = [
                    '=',
                    'is',
                    'is not',
                    'isnot',
                    '!=',
                    'contains',
                    'like',
                    'not in',
                    'notin',
                    'in',
                    'lt',
                    'gt',
                    'gt=',
                    'lt=',
                ];
                if ( str_contains( $content_values[ $index ], 'array(' ) && in_array( $operators[ $index ], [ 'in', 'notin', 'not in' ], true ) ) {
                    $json_string              = str_replace( [ 'array(', ')', "'" ], [ '[', ']', '"' ], $content_values[ $index ] );
                    $content_values[ $index ] = json_decode( $json_string, true );
                    $content_values[ $index ] = array_map(
                        fn( $value ) => GFCommon::replace_variables( $value, [], [] ),
                        $content_values[ $index ]
                    );

                    $field_filter = [
                        'key'   => $search_id,
                        'value' => $content_values[ $index ],
                    ];
                } else {
                    $field_filter = [
                        'key'   => $search_id,
                        'value' => GFCommon::replace_variables( $content_values[ $index ], [], [] ),
                    ];
                }

                if ( in_array( $operators[ $index ], $supported_operators, true ) ) {
                    $operators[ $index ]      = str_replace( 'gt', '>', $operators[ $index ] );
                    $operators[ $index ]      = str_replace( 'lt', '<', $operators[ $index ] );
                    $field_filter['operator'] = $operators[ $index ];
                }
            } else {
                $field_filter = [
                    'key'   => $search_id,
                    'value' => GFCommon::replace_variables( $content_values[ $index ], [], [] ),
                ];
            }

            $search_criteria['field_filters'][] = $field_filter;
        }

        $sorting = [
            'key'        => sanitize_text_field( $atts['sort_key'] ),
            'direction'  => in_array( strtoupper( $atts['sort_direction'] ), [ 'ASC', 'DESC', 'RAND' ], true ) ? strtoupper( $atts['sort_direction'] ) : 'DESC',
            'is_numeric' => ! ( strtolower( $atts['sort_is_num'] ) === 'false' ) && $atts['sort_is_num'],
        ];

        $secondary_sort_key       = sanitize_text_field( $atts['secondary_sort_key'] );
        $secondary_sort_direction = in_array( strtoupper( $atts['secondary_sort_direction'] ), [ 'ASC', 'DESC' ], true )
            ? strtoupper( $atts['secondary_sort_direction'] )
            : 'DESC';

        $paging_offset = 0;
        $total_count   = 0;

        if ( 'all' !== strtolower( $atts['limit'] ) ) {
            $original_limit = empty( $atts['limit'] ) ? 1 : (int) $atts['limit'];

            if ( $secondary_sort_key ) {
                $atts['limit'] = 'all';
            }
        }

        if ( empty( $atts['limit'] ) ) {
            $page_size = 1;
        } elseif ( 'all' === strtolower( $atts['limit'] ) ) {
            $page_size = 25;
        } else {
            $page_size = min( intVal( $atts['limit'] ), 25 );
        }
        $paging = [
            'offset'    => $paging_offset,
            'page_size' => $page_size,
        ];

        $entries = GFAPI::get_entries( $form_id, $search_criteria, $sorting, $paging, $total_count );

        if ( 'all' === $atts['limit'] || intVal( $atts['limit'] ) > 25 ) {
            $count = count( $entries );
            while ( $total_count > $count ) {
                $paging['offset'] += 25;
                $new_entries       = GFAPI::get_entries( $form_id, $search_criteria, $sorting, $paging, $total_count );
                array_push( $entries, ...$new_entries ); // $entries = array_merge( $entries, $new_entries );
                if ( is_numeric( $atts['limit'] ) && count( $entries ) > $atts['limit'] ) {
                    break;
                }
                $count = count( $entries );
            }
            if ( is_numeric( $atts['limit'] ) ) {
                $entries = array_slice( $entries, 0, intVal( $atts['limit'] ) );
            }
        }

        if ( empty( $entries ) ) {
            // If default contains multiple values, use the first one
            $default_values = array_map( 'trim', explode( '||', $atts['default'] ) );
            return wp_kses_post( $default_values[0] ?? '' );
        }

        if ( ! empty( $secondary_sort_key ) && 'RAND' !== $sorting['direction'] ) {
            $grouped_entries = [];
            foreach ( $entries as $entry ) {
                $primary_key_value                       = $entry[ $sorting['key'] ] ?? ''; // Use the primary sort key as the group key
                $grouped_entries[ $primary_key_value ][] = $entry;
            }

            // Sort each group based on the secondary sort key
            foreach ( $grouped_entries as &$group ) {
                usort(
                    $group,
                    function ( $entry1, $entry2 ) use ( $secondary_sort_key, $secondary_sort_direction ) {
                        $value1 = $entry1[ $secondary_sort_key ] ?? '';
                        $value2 = $entry2[ $secondary_sort_key ] ?? '';

                        // For non-numeric values, use string comparison
                        if ( ! is_numeric( $value1 ) || ! is_numeric( $value2 ) ) {
                            if ( strtoupper( $secondary_sort_direction ) === 'ASC' ) {
                                return strcasecmp( $value1, $value2 ); // Ascending order for strings
                            }

                            return strcasecmp( $value2, $value1 ); // Descending order for strings
                        }

                        // If numeric, compare numerically
                        $value1 = (float) $value1;
                        $value2 = (float) $value2;

                        if ( strtoupper( $secondary_sort_direction ) === 'ASC' ) {
                            return $value1 <=> $value2; // Ascending order for numbers
                        }

                        return $value2 <=> $value1; // Descending order for numbers
                    }
                );
            }

            unset( $group ); // Clean up the reference variable to avoid potential bugs

            // Flatten groups back into a single array, retaining primary sort order
            $entries = [];
            foreach ( $grouped_entries as $group ) {
                $entries = array_merge( $entries, $group );
            }
        }

        if ( isset( $original_limit ) && $original_limit < count( $entries ) ) {
            $entries = array_slice( $entries, 0, $original_limit );
        }

        $results = [];

        $atts['display'] = $this->convert_curly_shortcodes( $atts['display'] );

        // Mask nested gravops_search shortcodes [gravops_search ...]...[/gravops_search]
        // Mask only the display attribute value inside nested gravops_search shortcodes
        $nested_gravops_search_map = [];
        $masked_display            = $atts['display'];

        // Mask display attribute in [gravops_search ... display="..."]...[/gravops_search]
        $masked_display = preg_replace_callback(
            '/(\[gravops_search[^\]]*?\sdisplay=("|\')(.*?)(\2)[^\]]*\])/i',
            function ( $m ) use ( &$nested_gravops_search_map ) {
                $key                               = '__NESTED_GOSEARCH_DISPLAY_' . count( $nested_gravops_search_map ) . '__';
                $nested_gravops_search_map[ $key ] = $m[3];
                // Replace only the display value
                return str_replace( $m[3], $key, $m[0] );
            },
            $masked_display
        );

        // Updated regex: only match curly-brace {id}, {gos:id}, {gos:id;default} and plain gos:id (not just numbers)
        $regex = '/{(gos:)?([^{};]+)(;([^{}]+))?}|\bgos:([0-9]+)\b/';
        preg_match_all( $regex, $masked_display, $matches, PREG_SET_ORDER );

        $display_ids  = [];
        $tag_defaults = [];

        if ( empty( $matches ) ) {
            $display_ids = array_map( 'sanitize_text_field', explode( ',', $masked_display ) );
            $display_ids = array_map( 'trim', $display_ids );
        } else {
            foreach ( $matches as $match ) {
                // If curly-brace format, use those capture groups
                if ( isset( $match[2] ) && '' !== $match[2] ) {
                    $field_id = $match[2];
                    if ( ! empty( $match[4] ) ) {
                        $tag_defaults[ $field_id ] = $match[4];
                    }
                    $display_ids[] = sanitize_text_field( $field_id );
                    // If plain gos:id format
                } elseif ( isset( $match[5] ) && '' !== $match[5] ) {
                    $field_id      = $match[5];
                    $display_ids[] = sanitize_text_field( $field_id );
                }
            }
        }
        $display_ids = array_unique( $display_ids );

        $multi_input_present = false;

        // Parse default values
        $default_values = array_map( 'trim', explode( '||', $atts['default'] ) );
        $default_count  = count( $default_values );

        foreach ( $entries as $entry ) {
            $entry_results = [];
            foreach ( $display_ids as $index => $display_id ) {

                if ( 'meta' === $display_id ) {
                    if ( ! empty( $atts['separator'] ) ) {
                        $entry_results[ $display_id ] = implode( $atts['separator'], array_keys( $entry ) );
                    } else {
                        $entry_results[ $display_id ] = '<ul><li>' . implode( '</li><li>', array_keys( $entry ) ) . '</li></ul>';
                    }
                    continue;
                }
                if ( 'num_results' === $display_id ) {
                    continue;
                }

                $field = GFAPI::get_field( $entry['form_id'], $display_id );
                // phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                if ( $field && 'number' === $field->type ) {
                    $field_value = GFCommon::format_number( $entry[ $display_id ], $field['numberFormat'], $entry['currency'], true );
                } elseif ( $field && 'date' === $field->type ) {
                    $field_value = GFCommon::date_display( $entry[ $display_id ], 'Y-m-d', $field->dateFormat );
                } elseif ( $field && $this->is_multi_input_field( $field ) && ! str_contains( $display_id, '.' ) ) {
                    $multi_input_present = true;
                    $ids                 = array_column( $field['inputs'], 'id' );
                    $field_results       = [];
                    foreach ( $ids as $id ) {
                        if ( ! empty( $entry[ $id ] ) ) {
                            $field_results[] = $entry[ $id ];
                        }
                    }
                    $field_value = implode( ' ', $field_results );
                } else {
                    $field_value = $entry[ $display_id ] ?? '';
                    if ( '' === $field_value ) {
                        $temp = GFCommon::replace_variables( '{' . $display_id . '}', GFAPI::get_form( $entry['form_id'] ), $entry );
                        if ( '{' . $display_id . '}' !== $temp ) {
                            $field_value = $temp;
                        }
                    }
                }

                // Use default value if field value is empty
                if ( '' === $field_value || is_null( $field_value ) ) {
                    // Check if there's a tag-specific default value for this field
                    if ( isset( $tag_defaults[ $display_id ] ) ) {
                        $field_value = $tag_defaults[ $display_id ];
                    } elseif ( 1 === $default_count ) { // Otherwise use the global default values
                        // If there's only one default value, use it for all display values
                        $field_value = $default_values[0];
                    } elseif ( $index < $default_count ) {
                        // If there are multiple default values, use the corresponding one
                        $field_value = $default_values[ $index ];
                    } else {
                        $field_value = '';
                    }
                }

                $entry_results[ $display_id ] = $field_value;
            }

            // We only need to filter if the default value is empty
            if ( '' === $atts['default'] || is_null( $atts['default'] ) ) {
                $entry_results = array_filter( $entry_results, fn( $value ) => '' !== $value && ! is_null( $value ) );
            }
            if ( ! empty( $matches ) ) {
                $display_format = $masked_display;
                foreach ( $display_ids as $index => $display_id ) {
                    if ( 'num_results' === $display_id ) {
                        continue;
                    }

                    $value = $entry_results[ $display_id ] ?? '';

                    // If the value is empty and this is the first placeholder, use tag-specific default if available
                    if ( ! $value && 0 === $index ) {
                        if ( isset( $tag_defaults[ $display_id ] ) ) {
                            $value = $tag_defaults[ $display_id ];
                        } else {
                            $display_format = '';
                            break;
                        }
                    }

                    // Replace curly-brace formats first
                    $display_format = str_replace( '{gos:' . $display_id . '}', $value, $display_format );
                    $display_format = str_replace( '{' . $display_id . '}', $value, $display_format );
                    // Replace {gos:id;default-value} format
                    $pattern        = '/{gos:' . preg_quote( $display_id, '/' ) . ';[^{}]+}/';
                    $display_format = preg_replace( $pattern, $value, $display_format );
                    $pattern        = '/{' . preg_quote( $display_id, '/' ) . ';[^{}]+}/';
                    $display_format = preg_replace( $pattern, $value, $display_format );
                    // Replace plain gos:id only when not part of a larger word or attribute (not preceded/followed by [\w\.:])
                    $display_format = preg_replace( '/(?<![\w\.:])gos:' . preg_quote( $display_id, '/' ) . '(?![\w\.:])/', $value, $display_format );
                }
                // Restore masked display attributes in nested gravops_search
                if ( ! empty( $nested_gravops_search_map ) ) {
                    $display_format = strtr( $display_format, $nested_gravops_search_map );
                }
                $result_text = $display_format;
                if ( $atts['link'] ) {
                    $result_text = '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=gf_entries&view=entry&id=' . $entry['form_id'] . '&lid=' . $entry['id'] ) ) . '">' . $result_text . '</a>';
                }
                $results[] = $result_text;
            } else {
                $result_text = implode( ', ', $entry_results );
                if ( $atts['link'] ) {
                    $result_text = '<a target="_blank"  href="' . esc_url( admin_url( 'admin.php?page=gf_entries&view=entry&id=' . $entry['form_id'] . '&lid=' . $entry['id'] ) ) . '">' . $result_text . '</a>';
                }
                $results[] = $result_text;
            }
        }

        $results = array_map( 'trim', $results );
        $results = array_filter( $results, fn( $value ) => '' !== $value && ! is_null( $value ) );

        if ( empty( $results ) ) {
            // If default contains multiple values, use the first one
            $default_values = array_map( 'trim', explode( '||', $atts['default'] ) );
            return wp_kses_post( $default_values[0] ?? '' );
        }

        if ( empty( $atts['separator'] ) ) {
            $separator = ( count( $display_ids ) > 1 || $multi_input_present ) ? '; ' : ', ';
        } elseif ( strtolower( '__none__' ) === $atts['separator'] ) {
            $separator = '';
        } else {
            $separator = $atts['separator'];
        }

        // Process shortcodes first, then apply uniqueness to the final output
        $final_results = array_map(
            function ( $result ) use ( $allowed_tags ) {
                return wp_kses( do_shortcode( $result ), $allowed_tags );
            },
            $results
        );

        if ( $atts['unique'] ) {
            $final_results = array_unique( $final_results );
        }

        $final_results = array_map(
            function ( $result ) use ( $final_results ) {
                return str_replace( '{gos:num_results}', count( $final_results ), $result );
            },
            $final_results
        );

        return implode( $separator, $final_results );
    }

    /**
     * Determines if a given field is a multi-input field.
     *
     * @param mixed $field The field configuration array. Expected to contain 'type' and optionally 'inputType' keys.
     *
     * @return bool True if the field is a multi-input field, false otherwise.
     */
    private function is_multi_input_field( $field ): bool {
        return 'name' === $field['type'] || 'address' === $field['type'] || 'checkbox' === $field['type'] || ( ( 'image_choice' === $field['type'] || 'multi_choice' === $field['type'] ) && 'checkbox' === $field['inputType'] );
    }

    /**
     * Converts custom curly bracket shortcodes into standard WordPress-style shortcodes.
     *
     * Converts content with shortcodes in the format `{{shortcode attributes}}content{{/shortcode}}`
     * to `[shortcode attributes]content[/shortcode]`. Handles standalone shortcodes and unmatched closing tags.
     *
     * @param string $content The content containing curly bracket shortcodes.
     *
     * @return string The converted content with standard WordPress-style shortcodes.
     */
    private function convert_curly_shortcodes( $content ) {
        /* @var array<int, array{0: string, 1: int}> $open_match */
        while ( preg_match( '/\{\{(\w+)\b(.*?)\}\}/s', $content, $open_match, PREG_OFFSET_CAPTURE ) ) {
            $tag       = $open_match[1][0];
            $attrs     = $open_match[2][0];
            $start_pos = $open_match[0][1];
            $end_tag   = '{{/' . $tag . '}}';
            $end_pos   = strpos( $content, $end_tag, $start_pos );

            if ( false === $end_pos ) {
                break; // malformed shortcode
            }

            $open_len = strlen( $open_match[0][0] );
            $inner    = substr( $content, $start_pos + $open_len, $end_pos - $start_pos - $open_len );

            $replacement = '[' . $tag . $attrs . ']' . $inner . '[/' . $tag . ']';
            $content     = substr_replace( $content, $replacement, $start_pos, $end_pos + strlen( $end_tag ) - $start_pos );
        }

        // Handle standalone shortcodes like {{shortcode attr=...}} → [shortcode attr=...]
        $content = preg_replace_callback(
            '/\{\{(?!\/)([^\{\}\/]+?)\s*\}\}/',
            fn( $m ) => '[' . $m[1] . ']',
            $content
        );

        // Handle unmatched closing tags {{/shortcode}} → [/shortcode]
        return preg_replace( '/\{\{\/(\w+)\s*\}\}/', '[/$1]', $content );
    }
}
