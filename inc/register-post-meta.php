<?php
/**
 * Custom post type
 *
 * @package WC_Ifu_Docs
 */


namespace MGBdev\WC_Ifu_Docs;
require_once (IFUD_GLOBAl_DIR . 'inc/util-functions.php');

/**
 * Registers meta data and meta boxes for the `ifudoc` post type.
 */
 
 // In your plugin's main file or an included admin file

// get admin set languages using utility function 
$admin_lang_options = wcifu_get_supported_languages() ? wcifu_get_supported_languages() : array();

class My_Custom_Plugin_Metaboxes {

    public function __construct( $admin_lang_options ) {
		$this->lang_options = $admin_lang_options;
        add_action( 'add_meta_boxes_ifudoc', array( $this, 'add_plugin_metaboxes' ) ); // replace 'your_custom_post_type' with your CPT slug
        add_action( 'save_post_ifudoc', array( $this, 'save_plugin_metabox_data' ) ); // replace 'your_custom_post_type' with your CPT slug
    }

    public function add_plugin_metaboxes() {
        $metaboxes = array(
            array(
                'id'        => 'eng_usa_file',
                'title'     => __( 'English USA Document', 'wcifu-docs' ),
                'callback'  => array( $this, 'render_eng_usa_file' ),
                'context'   => 'normal',
                'priority'  => 'high',
                'fields'    => array(
                    'eng_usa_file_url' => array(
                        'type'  => 'text',
                        'label' => __( 'File URL', 'wcifu-docs' )
                    ),
                    'eng_usa_file_enabled' => array(
                        'type'  => 'checkbox',
                        'label' => __( 'Enable Feature', 'wcifu-docs' )
                    )
                )
            ),
			array(
                'id'        => 'eng_ce_file',
                'title'     => __( 'English CE Document URL', 'wcifu-docs' ),
                'callback'  => array( $this, 'render_eng_ce_file' ),
                'context'   => 'normal',
                'priority'  => 'high',
                'fields'    => array(
                    'eng_ce_file_url' => array(
                        'type'  => 'text',
                        'label' => __( 'File URL', 'wcifu-docs' )
                    ),
                    'eng_ce_file_enabled' => array(
                        'type'  => 'checkbox',
                        'label' => __( 'Enable Feature', 'wcifu-docs' )
                    )
                )
            ),
			array(
                'id'        => 'base_lang_file_url',
                'title'     => __( 'Base Document URL for translated language files', 'wcifu-docs' ),
                'callback'  => array( $this, 'render_base_lang_file_url' ),
                'context'   => 'normal',
                'priority'  => 'high',
                'fields'    => array(
                    'base_lang_file_url' => array(
                        'type'  => 'text',
                        'label' => __( 'File URL', 'wcifu-docs' )
                    ),
                    'base_lang_file_enabled' => array(
                        'type'  => 'checkbox',
                        'label' => __( 'Enable Feature', 'wcifu-docs' )
                    )
                )
            ),
            array(
                'id'        => 'lang_translations',
                'title'     => __( 'Translations / Languages', 'wcifu-docs' ),
				'description' => __( 'Language files will use the Base Document URL above + language code for filenames: e.g. Product_guideline_01_ESP.pdf', 'wcifu-docs' ),
                'callback'  => array( $this, 'render_lang_translations' ),
                'context'   => 'normal', 
                'priority'  => 'high',
                'fields'    => array(
                    'checked_langs' => array(
                        'type'  => 'array',
						'label' => __( 'Select Available Language Files', 'wcifu-docs' )
                    )
				),
			),
		);

        foreach ( $metaboxes as $metabox ) {
            add_meta_box(
                $metabox['id'],
                $metabox['title'],
                $metabox['callback'],
                'ifudoc', // replace with your CPT slug
                $metabox['context'],
                $metabox['priority'],
                $metabox // Pass the full metabox array as callback arguments
            );
        }
    }
	
	public function render_eng_usa_file( $post, $metabox ) {
        // Retrieve existing values
        $eng_usa_file_url = get_post_meta( $post->ID, 'eng_usa_file_url', true );
		$eng_usa_file_url_enabled = get_post_meta( $post->ID, '_eng_usa_file_url_enabled', true );	
		$is_checked = ((int)$eng_usa_file_url_enabled == 1) ? 'checked' : '';

        // Add a nonce field for security
        wp_nonce_field( 'wcifu_docs_save_metabox_data', 'wcifu_docs_metabox_nonce' );

        ?>
        <p>
            <label for="eng_usa_file_url"><?php echo esc_html( $metabox['args']['fields']['eng_usa_file_url']['label'] ); ?>:</label>
            <input type="text" id="eng_usa_file_url" name="eng_usa_file_url" value="<?php echo esc_attr( $eng_usa_file_url ); ?>" />
        </p>
        <p>
            <label for="eng_usa_file_enabled"><?php echo esc_html( $metabox['args']['fields']['eng_usa_file_enabled']['label'] ); ?>:</label>
            <input type="checkbox" id="_eng_usa_file_url_enabled" name="_eng_usa_file_url_enabled" value="1" <?php echo $is_checked; ?> />
        </p>
        <?php
    }
	
	public function render_eng_ce_file( $post, $metabox ) {
        // Retrieve existing values
        $eng_ce_file_url = get_post_meta( $post->ID, 'eng_ce_file_url', true );
		$eng_ce_file_url_enabled = get_post_meta( $post->ID, '_eng_ce_file_url_enabled', true );	
		$is_checked = ((int)$eng_ce_file_url_enabled == 1) ? 'checked' : '';

        // Add a nonce field for security
        wp_nonce_field( 'wcifu_docs_save_metabox_data', 'wcifu_docs_metabox_nonce' );

        ?>
        <p>
            <label for="eng_ce_file_url"><?php echo esc_html( $metabox['args']['fields']['eng_ce_file_url']['label'] ); ?>:</label>
            <input type="text" id="eng_ce_file_url" name="eng_ce_file_url" value="<?php echo esc_attr( $eng_ce_file_url ); ?>" />
        </p>
        <p>
            <label for="eng_ce_file_enabled"><?php echo esc_html( $metabox['args']['fields']['eng_ce_file_enabled']['label'] ); ?>:</label>
            <input type="checkbox" id="_eng_ce_file_url_enabled" name="_eng_ce_file_url_enabled" value="1" <?php echo $is_checked; ?> />
        </p>
        <?php
    }
	
		public function render_base_lang_file_url( $post, $metabox ) {
        // Retrieve existing values
        $base_lang_file_url = get_post_meta( $post->ID, 'base_lang_file_url', true );
		$base_lang_file_enabled = get_post_meta( $post->ID, '_base_lang_file_enabled', true );	
		$is_checked = ((int)$base_lang_file_enabled == 1) ? 'checked' : '';
		

        // Add a nonce field for security
        wp_nonce_field( 'wcifu_docs_save_metabox_data', 'wcifu_docs_metabox_nonce' );

        ?>
        <p>
            <label for="base_lang_file_url"><?php echo esc_html( $metabox['args']['fields']['base_lang_file_url']['label'] ); ?>:</label>
            <input type="text" id="base_lang_file_url" name="base_lang_file_url" value="<?php echo esc_attr( $base_lang_file_url ); ?>" />
        </p>
        <p>
            <label for="base_lang_file_enabled"><?php echo esc_html( $metabox['args']['fields']['base_lang_file_enabled']['label'] ); ?>:</label>
            <input type="checkbox" id="_base_lang_file_enabled" name="_base_lang_file_enabled" value="1" <?php echo $is_checked; ?> />

			
        </p>
        <?php
    }
	
	public function render_lang_translations( $post, $metabox ) {

        // Add a nonce field for security
        wp_nonce_field( 'wcifu_docs_save_metabox_data', 'wcifu_docs_metabox_nonce' );
		
		
		// Get previously saved values
		$checked_langs = get_post_meta( $post->ID, '_translations', true );
		if ( ! is_array( $checked_langs ) ) {
			$checked_langs = array();
		}
		
		// Define checkbox options from array set in woocommerce admin settings
		$lang_options = $this->lang_options;
		
		
		// Output HTML for language checkboxes
		?>
	
        <p> <label for="checked_langs"><?php echo esc_html( $metabox['args']['fields']['checked_langs']['label'] ); ?>:</label><br/><br/>
		<label>Select all languages:</label> <input type="checkbox" id="select-all-langs"><br/><br/>
		<?php 
		foreach ( $lang_options as $id => $data ) {
			$is_checked = array_key_exists($id, $checked_langs) ? 'checked' : '';

			?>
			<label>
				<input type="checkbox" class="lang-option" name="_checked_lang[<?php echo esc_html( $id ); ?>]" value="1" <?php echo $is_checked; ?> />
				<input type="hidden" name="_lang_label[<?php echo esc_html( $id ); ?>]" value="<?php echo esc_html( $data['label'] ); ?>">
				<input type="hidden" name="_lang_code[<?php echo esc_html( $id ); ?>]" value="<?php echo esc_attr( $data['code']); ?>">
			<?php
			echo esc_html( $data['label'] ); 
			echo '</label><br>';
			
		}
		?>
		</p>
        <?php

		
		add_action( 'admin_footer', function() { ?>
			<script>
			  document.addEventListener('DOMContentLoaded', function() {
			  console.log('selecting all languages script loaded');
			  const selectAllLangs = document.getElementById('select-all-langs');
			  const langCheckboxes = document.querySelectorAll('.lang-option');

			  selectAllLangs.addEventListener('change', function() {
				langCheckboxes.forEach(function(checkbox) {
				  checkbox.checked = selectAllLangs.checked;
				});
			  });

			  langCheckboxes.forEach(function(checkbox) {
				checkbox.addEventListener('change', function() {
				  // Check if all individual checkboxes are selected, then update the "Select All" checkbox
				  const allChecked = Array.from(langCheckboxes).every(cb => cb.checked);
				  selectAllLangs.checked = allChecked;
				});
			  });
			});
			</script>
		<?php });
		
	}


    public function save_plugin_metabox_data( $post_id ) {
        // Check if our nonce is set.
        if ( ! isset( $_POST['wcifu_docs_metabox_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['wcifu_docs_metabox_nonce'], 'wcifu_docs_save_metabox_data' ) ) {
            return;
        }

        // If this is an autosave, the form has not been submitted, so do nothing.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save each 'text' field from the metaboxes
        $fields_to_save_text = array( 'eng_usa_file_url', 'eng_ce_file_url', 'base_lang_file_url');

        foreach ( $fields_to_save_text as $field_name ) {
            if ( isset( $_POST[$field_name] ) ) {
                $value = sanitize_text_field( $_POST[$field_name] );
                update_post_meta( $post_id, $field_name, $value );
            } else {
                delete_post_meta( $post_id, $field_name ); // For checkboxes that are unchecked
            }
        }
		// Save each 'checkbox' field from the metaboxes
        $fields_to_save_checkbox = array( '_eng_usa_file_url_enabled', '_eng_ce_file_url_enabled', '_base_lang_file_enabled' );

        foreach ( $fields_to_save_checkbox as $field_name ) {
            $checked = isset( $_POST[$field_name] ) && $_POST[$field_name] == 1;
			$checked = (int)$checked;
			update_post_meta( $post_id, $field_name, $checked );

        }
		
		// Save arrays for ENG language files (enabled, base urls, filenames)
		if ( isset($_POST['eng_usa_file_url']) ) {
			$url = esc_html($_POST['eng_usa_file_url']);
			$eng_usa_file = array(
				'code' => 'ENGUSA',
				'label' => 'English USA Customers',
				'active' => $_POST['_eng_usa_file_url_enabled'],
				'url_base' => dirname($url),
				'filename' =>  basename($url),
			);
			update_post_meta( $post_id, '_eng_usa_file', $eng_usa_file );
		}
		
		if ( isset($_POST['eng_ce_file_url']) ) {
			$url = esc_html($_POST['eng_ce_file_url']);
			$eng_ce_file = array(
				'code' => 'ENGCE',
				'label' => 'English CE Customers',
				'active' => $_POST['_eng_ce_file_url_enabled'],
				'url_base' => dirname($url),
				'filename' =>  basename($url),
			);
			update_post_meta( $post_id, '_eng_ce_file', $eng_ce_file );
		}
		
		
		// Save associative array of checked languages
		// TODO: error handling for missing baseurl
		if ( isset( $_POST['_checked_lang'] ) ) {
			$checked_langs = $_POST['_checked_lang'];
			$langLabels = $_POST['_lang_label'];
			$langCodes = $_POST['_lang_code'];
			
			$update_checked = array();
			$update_translations = array();
			
			// map values and labels to saved meta in array
			foreach ( $checked_langs as $id => $value) {
				$checked = isset( $checked_langs[$id] ) && $value == 1;
				$checked = (int)$checked;
				$update_checked[$id] = $checked; // update array of checked lang ids
				$url = esc_html($_POST['base_lang_file_url'] );
				$label = $langLabels[$id];
				$code = $langCodes[$id];
				$update_translations[$id] = array(
					'checked' => 1,
					'code' => $code,
					'label' => $label,
					'url_base' => dirname($url),
					'filename' =>  basename($url) . $code . '.pdf',
				);
			}
			update_post_meta( $post_id, '_checked_lang', $update_checked );
			update_post_meta( $post_id, '_translations', $update_translations );
		} else {
			delete_post_meta( $post_id, '_checked_lang' ); // Delete if no options are selected
			delete_post_meta( $post_id, '_translations' );
		}
		
		
    }
}

// Instantiate the class to activate the metaboxes
new My_Custom_Plugin_Metaboxes($admin_lang_options);
