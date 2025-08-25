<?php

/**
 * Example implementation for custom post type, taxonomy, and meta boxes
 * This demonstrates WordPress best practices for custom content types
 * 
 * EFFICIENCY OPTIMIZATIONS INCLUDED:
 * - Meta box rendering uses PHP templating instead of string concatenation
 * - Single get_post_meta() call for all fields to reduce database queries
 * - Batch processing of meta updates/deletes in save function
 * - Static caching system for repeated meta field access
 * - Optimized frontend display methods with sprintf/printf
 * - Enhanced JSON meta box with copy functionality and data summary
 * - Proper escaping and sanitization throughout
 * - Separated validation logic into dedicated methods
 * - Cache clearing after save operations for data consistency
 * 
 * @package MGBdev\Eifu_Docs_Class
 */

namespace MGBdev\Eifu_Docs_Class;


/**
 * Example implementation for custom post type, taxonomy, and meta boxes
 * This demonstrates WordPress best practices for custom content types
 * 
 * EFFICIENCY OPTIMIZATIONS INCLUDED:
 * - Meta box rendering uses PHP templating instead of string concatenation
 * - Single get_post_meta() call for all fields to reduce database queries
 * - Batch processing of meta updates/deletes in save function
 * - Static caching system for repeated meta field access
 * - Optimized frontend display methods with sprintf/printf
 * - Enhanced JSON meta box with copy functionality and data summary
 * - Proper escaping and sanitization throughout
 * - Separated validation logic into dedicated methods
 * - Cache clearing after save operations for data consistency
 * 
 * @package MGBdev\Eifu_Docs_Class
 */

/**
 * Class Eifu_Custom_Content
 * 
 * Handles registration of custom post type EIFUC_G_PT, custom taxonomy EIFUC_G_TX,
 * and related meta boxes with URL fields, checkboxes, and JSON storage.
 */
class IFU_Post_Type {
    
    /**
     * Meta field keys for the custom fields
     */
    const META_FIELD_PREFIX = '_eifu_';    const URL_FIELDS = [
        'website_url' => 'Website URL',
        'download_url' => 'Download URL', 
        'support_url' => 'Support URL',
        'documentation_url' => 'Documentation URL'
    ];
    const CHECKBOX_FIELDS = [
        'is_featured' => 'Featured Item',
        'is_premium' => 'Premium Content',
        'requires_auth' => 'Requires Authentication'
    ];
    const JSON_META_KEY = '_eifu_json_data';
    
    /**
     * Initialize the class by hooking into WordPress
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomy' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta_fields' ] );
    }
    
    /**
     * Register the custom post type EIFUC_G_PT
     */
    public function register_post_type() {
        $labels = [
            'name'                  => _x( 'IFU Items', 'Post type general name', EIFUC_G_TD),
            'singular_name'         => _x( 'IFU Item', 'Post type singular name', EIFUC_G_TD),
            'menu_name'             => _x( 'IFU Items', 'Admin Menu text', EIFUC_G_TD),
            'name_admin_bar'        => _x( 'IFU Item', 'Add New on Toolbar', EIFUC_G_TD),
            'add_new'               => __( 'Add New', EIFUC_G_TD),
            'add_new_item'          => __( 'Add New IFU Item', EIFUC_G_TD),
            'new_item'              => __( 'New IFU Item', EIFUC_G_TD),
            'edit_item'             => __( 'Edit IFU Item', EIFUC_G_TD),
            'view_item'             => __( 'View IFU Item', EIFUC_G_TD),
            'all_items'             => __( 'All IFU Items', EIFUC_G_TD),
            'search_items'          => __( 'Search IFU Items', EIFUC_G_TD),
            'parent_item_colon'     => __( 'Parent IFU Items:', EIFUC_G_TD),
            'not_found'             => __( 'No IFU Items found.', EIFUC_G_TD),
            'not_found_in_trash'    => __( 'No IFU Items found in Trash.', EIFUC_G_TD),
            'featured_image'        => _x( 'IFU Item Cover Image', 'Overrides the "Featured Image" phrase', EIFUC_G_TD),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', EIFUC_G_TD),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', EIFUC_G_TD),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', EIFUC_G_TD),
            'archives'              => _x( 'IFU Item archives', 'The post type archive label used in nav menus', EIFUC_G_TD),
            'insert_into_item'      => _x( 'Insert into IFU Item', 'Overrides the "Insert into post" phrase', EIFUC_G_TD),
            'uploaded_to_this_item' => _x( 'Uploaded to this IFU Item', 'Overrides the "Uploaded to this post" phrase', EIFUC_G_TD),
            'filter_items_list'     => _x( 'Filter IFU Items list', 'Screen reader text for the filter links', EIFUC_G_TD),
            'items_list_navigation' => _x( 'IFU Items list navigation', 'Screen reader text for the pagination', EIFUC_G_TD),
            'items_list'            => _x( 'IFU Items list', 'Screen reader text for the items list', EIFUC_G_TD),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true, // Enable Gutenberg editor
            'query_var'          => true,
            'rewrite'            => [ 'slug' => 'ifu' ],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-admin-tools',
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', ],
            'taxonomies'         => [ EIFUC_G_TX ],
        ];

        register_post_type( EIFUC_G_PT, $args );
    }
    
    /**
     * Register the custom taxonomy EIFUC_G_TX
     */
    public function register_taxonomy() {
        $labels = [
            'name'                       => _x( 'Eifu Categories', 'Taxonomy General Name', EIFUC_G_TD),
            'singular_name'              => _x( 'Eifu Category', 'Taxonomy Singular Name', EIFUC_G_TD),
            'menu_name'                  => __( 'Eifu Categories', EIFUC_G_TD),
            'all_items'                  => __( 'All Categories', EIFUC_G_TD),
            'parent_item'                => __( 'Parent Category', EIFUC_G_TD),
            'parent_item_colon'          => __( 'Parent Category:', EIFUC_G_TD),
            'new_item_name'              => __( 'New Category Name', EIFUC_G_TD),
            'add_new_item'               => __( 'Add New Category', EIFUC_G_TD),
            'edit_item'                  => __( 'Edit Category', EIFUC_G_TD),
            'update_item'                => __( 'Update Category', EIFUC_G_TD),
            'view_item'                  => __( 'View Category', EIFUC_G_TD),
            'separate_items_with_commas' => __( 'Separate categories with commas', EIFUC_G_TD),
            'add_or_remove_items'        => __( 'Add or remove categories', EIFUC_G_TD),
            'choose_from_most_used'      => __( 'Choose from the most used', EIFUC_G_TD),
            'popular_items'              => __( 'Popular Categories', EIFUC_G_TD),
            'search_items'               => __( 'Search Categories', EIFUC_G_TD),
            'not_found'                  => __( 'Not Found', EIFUC_G_TD),
            'no_terms'                   => __( 'No categories', EIFUC_G_TD),
            'items_list'                 => __( 'Categories list', EIFUC_G_TD),
            'items_list_navigation'      => __( 'Categories list navigation', EIFUC_G_TD),
        ];

        $args = [
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true, // Enable Gutenberg editor
            'rewrite'                    => [ 'slug' => EIFUC_G_TX ],
        ];

        register_taxonomy( EIFUC_G_TX, [ EIFUC_G_PT ], $args );
    }
    
    /**
     * Add meta boxes to the EIFUC_G_PT post type
     */
    public function add_meta_boxes() {
        // Main meta box for URL fields and checkboxes
        add_meta_box(
            'eifu_fields_meta_box',
            __( 'IFU Item Settings', EIFUC_G_TD),
            [ $this, 'render_fields_meta_box' ],
            EIFUC_G_PT,
            'normal',
            'high'
        );
        
        // JSON data meta box
        add_meta_box(
            'eifu_json_meta_box',
            __( 'JSON Data Storage', EIFUC_G_TD),
            [ $this, 'render_json_meta_box' ],
            EIFUC_G_PT,
            'normal',
            'default'
        );
    }
    
    /**
     * Render the main meta box with URL fields and checkboxes
     * 
     * @param WP_Post $post The post object
     */
    public function render_fields_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'eifu_save_meta_fields', 'eifu_meta_nonce' );
        
        // Get all meta values at once for efficiency
        $meta_values = get_post_meta( $post->ID );
        ?>
        
        <table class="form-table">
            <tr><td colspan="2"><h3><?php esc_html_e( 'URL Fields', EIFUC_G_TD); ?></h3></td></tr>
            
            <?php foreach ( self::URL_FIELDS as $field_key => $field_label ) : ?>
                <?php 
                $meta_key = self::META_FIELD_PREFIX . $field_key;
                $value = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : '';
                ?>
                <tr>
                    <th scope="row">
                        <label for="<?php echo esc_attr( $meta_key ); ?>"><?php echo esc_html( $field_label ); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="<?php echo esc_attr( $meta_key ); ?>" 
                               name="<?php echo esc_attr( $meta_key ); ?>" 
                               value="<?php echo esc_attr( $value ); ?>" 
                               class="regular-text" />
                    </td>
                </tr>
            <?php endforeach; ?>
            
            <tr><td colspan="2"><h3><?php esc_html_e( 'Options', EIFUC_G_TD); ?></h3></td></tr>
            
            <?php foreach ( self::CHECKBOX_FIELDS as $field_key => $field_label ) : ?>
                <?php 
                $meta_key = self::META_FIELD_PREFIX . $field_key;
                $value = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : '';
                ?>
                <tr>
                    <th scope="row"><?php echo esc_html( $field_label ); ?></th>
                    <td>
                        <label for="<?php echo esc_attr( $meta_key ); ?>">
                            <input type="checkbox" 
                                   id="<?php echo esc_attr( $meta_key ); ?>" 
                                   name="<?php echo esc_attr( $meta_key ); ?>" 
                                   value="1" 
                                   <?php checked( $value, '1' ); ?> />
                            <?php echo esc_html( $field_label ); ?>
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <?php
    }
    
    /**
     * Render the JSON data meta box
     * 
     * @param WP_Post $post The post object
     */
    public function render_json_meta_box( $post ) {
        $json_data = get_post_meta( $post->ID, self::JSON_META_KEY, true );
        ?>
        
        <div class="eifu-json-meta-box">
            <p><strong><?php esc_html_e( 'JSON Encoded Metadata', EIFUC_G_TD); ?></strong></p>
            <p><em><?php esc_html_e( 'This field automatically stores all the above metadata as JSON when the post is saved.', EIFUC_G_TD); ?></em></p>
            
            <?php if ( $json_data ) : ?>
                <textarea readonly rows="10" cols="50" style="width: 100%; font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $json_data ); ?></textarea>
                
                <p>
                    <button type="button" class="button button-secondary" onclick="this.previousElementSibling.select(); document.execCommand('copy'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy JSON', 2000);">
                        <?php esc_html_e( 'Copy JSON', EIFUC_G_TD); ?>
                    </button>
                </p>
                
                <?php 
                $decoded_data = json_decode( $json_data, true );
                if ( $decoded_data && is_array( $decoded_data ) ) :
                ?>
                    <details style="margin-top: 15px;">
                        <summary style="cursor: pointer; font-weight: bold;"><?php esc_html_e( 'Data Summary', EIFUC_G_TD); ?></summary>
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li><?php printf( esc_html__( 'Total fields: %d', EIFUC_G_TD), count( $decoded_data ) ); ?></li>
                            <li><?php printf( esc_html__( 'Last updated: %s', EIFUC_G_TD), isset( $decoded_data['last_updated'] ) ? esc_html( $decoded_data['last_updated'] ) : esc_html__( 'Unknown', EIFUC_G_TD) ); ?></li>
                            <?php if ( isset( $decoded_data['categories'] ) && is_array( $decoded_data['categories'] ) && ! empty( $decoded_data['categories'] ) ) : ?>
                                <li><?php printf( esc_html__( 'Categories: %s', EIFUC_G_TD), esc_html( implode( ', ', $decoded_data['categories'] ) ) ); ?></li>
                            <?php endif; ?>
                        </ul>
                    </details>
                <?php endif; ?>
                
            <?php else : ?>
                <p><?php esc_html_e( 'No JSON data saved yet. Save the post to generate JSON data.', EIFUC_G_TD); ?></p>
            <?php endif; ?>
        </div>
        
        <?php
    }
    
    /**
     * Save meta fields when post is saved
     * 
     * @param int $post_id The post ID
     */
    public function save_meta_fields( $post_id ) {
        // Performance check: early returns for invalid saves
        if ( $this->should_skip_save( $post_id ) ) {
            return;
        }
        
        $meta_data = [];
        $meta_updates = [];
        $meta_deletes = [];
        
        // Process URL fields efficiently
        foreach ( self::URL_FIELDS as $field_key => $field_label ) {
            $meta_key = self::META_FIELD_PREFIX . $field_key;
            $value = isset( $_POST[ $meta_key ] ) ? esc_url_raw( trim( $_POST[ $meta_key ] ) ) : '';
            
            if ( ! empty( $value ) ) {
                $meta_updates[ $meta_key ] = $value;
                $meta_data[ $field_key ] = $value;
            } else {
                $meta_deletes[] = $meta_key;
            }
        }
        
        // Process checkbox fields efficiently
        foreach ( self::CHECKBOX_FIELDS as $field_key => $field_label ) {
            $meta_key = self::META_FIELD_PREFIX . $field_key;
            $is_checked = isset( $_POST[ $meta_key ] ) && $_POST[ $meta_key ] === '1';
            
            if ( $is_checked ) {
                $meta_updates[ $meta_key ] = '1';
                $meta_data[ $field_key ] = true;
            } else {
                $meta_deletes[] = $meta_key;
                $meta_data[ $field_key ] = false;
            }
        }
        
        // Batch update/delete meta fields for better performance
        foreach ( $meta_updates as $key => $value ) {
            update_post_meta( $post_id, $key, $value );
        }
        
        foreach ( $meta_deletes as $key ) {
            delete_post_meta( $post_id, $key );
        }
        
        // Build comprehensive JSON data
        $meta_data = array_merge( $meta_data, [
            'post_id' => $post_id,
            'post_title' => get_the_title( $post_id ),
            'post_status' => get_post_status( $post_id ),
            'last_updated' => current_time( 'mysql' ),
            'categories' => $this->get_post_categories( $post_id ),
        ] );
        
        // Encode and save JSON data
        $json_data = wp_json_encode( $meta_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
        update_post_meta( $post_id, self::JSON_META_KEY, $json_data );
        
        // Clear cache after save to ensure fresh data on next load
        self::clear_meta_cache( $post_id );
    }
    
    /**
     * Determine if the save operation should be skipped
     * 
     * @param int $post_id The post ID
     * @return bool True if save should be skipped
     */
    private function should_skip_save( $post_id ) {
        // Check if this is an autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return true;
        }
        
        // Check if this is the correct post type
        if ( get_post_type( $post_id ) !== EIFUC_G_PT ) {
            return true;
        }
        
        // Check the nonce
        if ( ! isset( $_POST['eifu_meta_nonce'] ) || ! wp_verify_nonce( $_POST['eifu_meta_nonce'], 'eifu_save_meta_fields' ) ) {
            return true;
        }
        
        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get post categories efficiently
     * 
     * @param int $post_id The post ID
     * @return array Array of category names
     */
    private function get_post_categories( $post_id ) {
        $terms = get_the_terms( $post_id, EIFUC_G_TX );
        return ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : [];
    }
    
    /**
     * Static cache for meta values to avoid duplicate database queries
     */
    private static $meta_cache = [];
    
    /**
     * Get meta field value with caching
     * 
     * @param int    $post_id The post ID
     * @param string $field_key The field key (without prefix)
     * @param mixed  $default Default value if not found
     * @return mixed The meta value
     */
    public static function get_meta_field( $post_id, $field_key, $default = '' ) {
        // Check cache first
        $cache_key = $post_id . '_' . $field_key;
        if ( isset( self::$meta_cache[ $cache_key ] ) ) {
            return self::$meta_cache[ $cache_key ];
        }
        
        $meta_key = self::META_FIELD_PREFIX . $field_key;
        $value = get_post_meta( $post_id, $meta_key, true ) ?: $default;
        
        // Cache the result
        self::$meta_cache[ $cache_key ] = $value;
        
        return $value;
    }
    
    /**
     * Get all meta fields for a post efficiently
     * 
     * @param int $post_id The post ID
     * @return array Associative array of field_key => value
     */
    public static function get_all_meta_fields( $post_id ) {
        $meta_values = get_post_meta( $post_id );
        $result = [];
        
        foreach ( array_merge( self::URL_FIELDS, self::CHECKBOX_FIELDS ) as $field_key => $field_label ) {
            $meta_key = self::META_FIELD_PREFIX . $field_key;
            $result[ $field_key ] = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : '';
        }
        
        return $result;
    }
    
    /**
     * Get JSON data for a post with caching
     * 
     * @param int $post_id The post ID
     * @return array|false The decoded JSON data or false if not found
     */
    public static function get_json_data( $post_id ) {
        $cache_key = $post_id . '_json';
        if ( isset( self::$meta_cache[ $cache_key ] ) ) {
            return self::$meta_cache[ $cache_key ];
        }
        
        $json_data = get_post_meta( $post_id, self::JSON_META_KEY, true );
        $decoded_data = $json_data ? json_decode( $json_data, true ) : false;
        
        // Cache the result
        self::$meta_cache[ $cache_key ] = $decoded_data;
        
        return $decoded_data;
    }
    
    /**
     * Helper method to display URL fields in frontend (optimized)
     * 
     * @param int $post_id The post ID
     */
    public static function display_url_fields( $post_id ) {
        $meta_fields = self::get_all_meta_fields( $post_id );
        $output = [];
        
        foreach ( self::URL_FIELDS as $field_key => $field_label ) {
            $value = $meta_fields[ $field_key ] ?? '';
            if ( ! empty( $value ) ) {
                $output[] = sprintf(
                    '<p><strong>%s:</strong> <a href="%s" target="_blank" rel="noopener">%s</a></p>',
                    esc_html( $field_label ),
                    esc_url( $value ),
                    esc_html( $value )
                );
            }
        }
        
        if ( ! empty( $output ) ) {
            echo '<div class="eifu-url-fields">' . implode( '', $output ) . '</div>';
        }
    }
    
    /**
     * Helper method to display checkbox fields in frontend (optimized)
     * 
     * @param int $post_id The post ID
     */
    public static function display_checkbox_fields( $post_id ) {
        $meta_fields = self::get_all_meta_fields( $post_id );
        $active_options = [];
        
        foreach ( self::CHECKBOX_FIELDS as $field_key => $field_label ) {
            if ( ! empty( $meta_fields[ $field_key ] ) ) {
                $active_options[] = $field_label;
            }
        }
        
        if ( ! empty( $active_options ) ) {
            printf(
                '<div class="eifu-checkbox-fields"><p><strong>%s</strong> %s</p></div>',
                esc_html__( 'Features:', EIFUC_G_TD),
                esc_html( implode( ', ', $active_options ) )
            );
        }
    }
    
    /**
     * Clear meta cache for a specific post (useful after save operations)
     * 
     * @param int $post_id The post ID
     */
    public static function clear_meta_cache( $post_id ) {
        foreach ( self::$meta_cache as $key => $value ) {
            if ( strpos( $key, $post_id . '_' ) === 0 ) {
                unset( self::$meta_cache[ $key ] );
            }
        }
    }
}