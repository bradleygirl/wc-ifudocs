<?php
/**
 * Document taxonomy registration and management
 *
 * @package WC_Ifu_Docs
 */

namespace MGBdev\WC_Ifu_Docs;

/**
 * Register document categories taxonomy
 */
function register_document_taxonomy() {
    register_taxonomy(
        'wcifudoc_category',
        'ifudoc',
        array(
            'labels' => array(
                'name' => __('Document Categories', 'wcifu-docs'),
                'singular_name' => __('Document Category', 'wcifu-docs'),
                'menu_name' => __('Categories', 'wcifu-docs'),
            ),
            'hierarchical' => true,
            'public' => true,
			'publicly_queryable'	=> true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
			'sort'			=> true,
            'rewrite' => array(
				'slug' => 'ifu',
				'with_front' => false,
				'hierarchical' => true
				),
        )
    );

    // Document tags
    register_taxonomy(
        'wcifudoc_tag',
        'ifudoc',
        array(
            'labels' => array(
                'name' => __('Document Tags', 'wcifu-docs'),
                'singular_name' => __('Document Tag', 'wcifu-docs'),
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'document-tag'),
        )
    );
}
add_action('init', __NAMESPACE__ . '\\register_document_taxonomy'); 


register_term_meta('wcifudoc_category', '_wcifu_show_in_archive', ['show_in_rest' => true]);


add_action('wcifudoc_category_add_form_fields', __NAMESPACE__ . '\\wcifudoc_category_show_add_form_fields');

function wcifudoc_category_show_add_form_fields(){
    ?>
  
    <div class="form-field">
      <label for="wcifu_show_in_archive">Show in Archive Page</label>
      <input name="_wcifu_show_in_archive" id="wcifu_show_in_archive" type="checkbox" value="1" checked >
      <p id="wcifu_show_in_archive-description">Check this box to show this category in the archive page.</p>
    </div>
  
<?php }
add_action('wcifudoc_category_add_form_fields',  __NAMESPACE__ . '\\wcifudoc_save_category_meta');

function wcifudoc_save_category_meta($term_id){
    if(!isset($_POST['_wcifu_show_in_archive'])){
      return;
    }
    update_term_meta( $term_id, '_wcifu_show_in_archive', intval( $_POST['_wcifu_show_in_archive'] ));
  }

  add_action('wcifudoc_category_edit_form_fields',  __NAMESPACE__ . '\\wcifudoc_category_show_edit_form_fields');

  function wcifudoc_category_show_edit_form_fields($term) { 
    
    $show_term = get_term_meta( $term->term_id, '_wcifu_show_in_archive', true );
    // The third argument is whether it is singular: true tells it not to return an array, just the one value
    $is_checked = ((int)$show_term == 1) ? 'checked' : '';
    ?>
      <tr class="form-field">
              <th scope="row"><label for="up_more_info_url">Show in Archive Page</label></th>
              <td><input name="_wcifu_show_in_archive" id="wcifu_show_in_archive" type="checkbox" value="1" <?php echo $is_checked; ?> >
              <p class="description">Check this box to show this category in the archive page.</p></td>
          </tr>
  <?php }

add_action('edited_wcifudoc_category',  __NAMESPACE__ . '\\wcifudoc_save_category_meta');