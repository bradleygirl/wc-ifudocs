<?php
/**
 * Custom post type
 *
 * @package WC_Ifu_Docs
 */


namespace MGBdev\WC_Ifu_Docs;

/**
 * Registers the `ifudoc` post type.
 */
 
 function wcifu_docs_post_type_init() {
	register_post_type(
		'ifudoc',
		array(
			'labels'                => array(
				'name'                  => __( 'IFU Documents', 'wcifu-docs' ),
				'singular_name'         => __( 'IFU Document', 'wcifu-docs' ),
				'all_items'             => __( 'All IFU Documents', 'wcifu-docs' ),
				'archives'              => __( 'IFU Document Archives', 'wcifu-docs' ),
				'attributes'            => __( 'IFU Document Attributes', 'wcifu-docs' ),
				'insert_into_item'      => __( 'Insert into IFU Document', 'wcifu-docs' ),
				'uploaded_to_this_item' => __( 'Uploaded to this IFU Document', 'wcifu-docs' ),
				'featured_image'        => _x( 'Featured Image', 'ifudoc', 'wcifu-docs' ),
				'set_featured_image'    => _x( 'Set featured image', 'ifudoc', 'wcifu-docs' ),
				'remove_featured_image' => _x( 'Remove featured image', 'ifudoc', 'wcifu-docs' ),
				'use_featured_image'    => _x( 'Use as featured image', 'ifudoc', 'wcifu-docs' ),
				'filter_items_list'     => __( 'Filter IFU Documents list', 'wcifu-docs' ),
				'items_list_navigation' => __( 'IFU Documents list navigation', 'wcifu-docs' ),
				'items_list'            => __( 'IFU Documents list', 'wcifu-docs' ),
				'new_item'              => __( 'New IFU Document', 'wcifu-docs' ),
				'add_new'               => __( 'Add New', 'wcifu-docs' ),
				'add_new_item'          => __( 'Add New IFU Document', 'wcifu-docs' ),
				'edit_item'             => __( 'Edit IFU Document', 'wcifu-docs' ),
				'view_item'             => __( 'View IFU Document', 'wcifu-docs' ),
				'view_items'            => __( 'View IFU Documents', 'wcifu-docs' ),
				'search_items'          => __( 'Search IFU Documents', 'wcifu-docs' ),
				'not_found'             => __( 'No IFU Documents found', 'wcifu-docs' ),
				'not_found_in_trash'    => __( 'No IFU Documents found in trash', 'wcifu-docs' ),
				'parent_item_colon'     => __( 'Parent IFU Document:', 'wcifu-docs' ),
				'menu_name'             => __( 'IFU Documents', 'wcifu-docs' ),
			),
			'public'                => true,
			'publicly_queryable'	=> true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => false,
			'supports'              => array( 'title', 'editor', 'excerpt', 'revisions', 'page-attributes' ),
			'has_archive'           => 'ifu',
			'rewrite'               => array('slug' => 'document', 'with_front' => false),
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-media-document',
			'show_in_rest'          => true,
			'rest_base'             => 'ifudoc',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rest_namespace' 		=> 'wp/v2',
			
		)
	);
	
}

add_action( 'init', 'MGBdev\\WC_Ifu_Docs\\wcifu_docs_post_type_init' );

add_action( 'add_meta_boxes', function() {
	add_meta_box( 'misha_test', 'Meta Box for Select2', 'rudr_display_metabox', 'post' );
} );

/**
 * Sets the post updated messages for the `eifudoc` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `eifudoc` post type.
 */
function wcifu_docs_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['ifudoc'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'IFU Document updated. <a target="_blank" href="%s">View IFU Document</a>', 'wcifu-docs' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'wcifu-docs' ),
		3  => __( 'Custom field deleted.', 'wcifu-docs' ),
		4  => __( 'IFU Document updated.', 'wcifu-docs' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'IFU Document restored to revision from %s', 'wcifu-docs' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'IFU Document published. <a href="%s">View IFU Document</a>', 'wcifu-docs' ), esc_url( $permalink ) ),
		7  => __( 'IFU Document saved.', 'wcifu-docs' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'IFU Document submitted. <a target="_blank" href="%s">Preview IFU Document</a>', 'wcifu-docs' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'IFU Document scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview IFU Document</a>', 'wcifu-docs' ), date_i18n( __( 'M j, Y @ G:i', 'wcifu-docs' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'IFU Document draft updated. <a target="_blank" href="%s">Preview IFU Document</a>', 'wcifu-docs' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'MGBdev\\WC_Ifu_Docs\\wcifu_docs_updated_messages' );

/**
 * Sets the bulk post updated messages for the `ifudoc` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `ifudoc` post type.
 */
function wcifu_docs_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['ifudoc'] = array(
		/* translators: %s: Number of IFU Documents. */
		'updated'   => _n( '%s IFU Document updated.', '%s IFU Documents updated.', $bulk_counts['updated'], 'wcifu-docs' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 IFU Document not updated, somebody is editing it.', 'wcifu-docs' ) :
						/* translators: %s: Number of IFU Documents. */
						_n( '%s IFU Document not updated, somebody is editing it.', '%s IFU Documents not updated, somebody is editing them.', $bulk_counts['locked'], 'wcifu-docs' ),
		/* translators: %s: Number of IFU Documents. */
		'deleted'   => _n( '%s IFU Document permanently deleted.', '%s IFU Documents permanently deleted.', $bulk_counts['deleted'], 'wcifu-docs' ),
		/* translators: %s: Number of IFU Documents. */
		'trashed'   => _n( '%s IFU Document moved to the Trash.', '%s IFU Documents moved to the Trash.', $bulk_counts['trashed'], 'wcifu-docs' ),
		/* translators: %s: Number of IFU Documents. */
		'untrashed' => _n( '%s IFU Document restored from the Trash.', '%s IFU Documents restored from the Trash.', $bulk_counts['untrashed'], 'wcifu-docs' ),
	);

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'MGBdev\\WC_Ifu_Docs\\wcifu_docs_bulk_updated_messages', 10, 2 );

/**
 * Add CSV upload form for importing documents
 */
 
 function wcifu_docs_add_submenu_upload_csv() {
    add_submenu_page(
        'edit.php?post_type=ifudoc', 			// Parent slug
        'Import IFU documents as CSV Data',     // Page title
        'Import documents as CSV',              // Menu title
        'manage_options',                       // Capability
        'wcifu_csv_import',                     // Submenu slug
        'MGBdev\\WC_Ifu_Docs\\wcifu_csv_import_docs'             // Callback function
    );
}

add_action('admin_menu', 'MGBdev\\WC_Ifu_Docs\\wcifu_docs_add_submenu_upload_csv');

// Callback function for the submenu page
function wcifu_csv_import_docs() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wcifu-docs' ) );
		}
    ?>
    <div class="wrap">
        <h1>CSV Importer for IFU documents</h1>
        <?php
		 ?>
		 <div id="wcifu-csv-form">
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="csv_file" accept=".csv">
				<input type="submit" name="submit_csv" value="Upload & Import Documents" class="button button-primary">
				<?php wp_nonce_field('wcifu_csv_import_nonce_action', 'wcifu_csv_import_nonce_field'); ?>
			</form>
		</div>
    </div>
	<style>
	#wcifu-csv-form {
	  margin-top: 4em;
	  padding:2em;
	  width: fit-content; 
	  border: 2px solid gray
	}
	</style>
    <?php
        if ( isset( $_POST['submit_csv'] ) && check_admin_referer( 'wcifu_csv_import_nonce_action', 'wcifu_csv_import_nonce_field' ) ) {
			if ( ! empty( $_FILES['csv_file']['name'] ) && 'csv' == pathinfo( $_FILES['csv_file']['name'], PATHINFO_EXTENSION ) ) {
				$file_path = $_FILES['csv_file']['tmp_name'];
				$handle = fopen( $file_path, 'r' );

				if ( $handle !== FALSE ) {
					// Read the header row
					$header = fgetcsv( $handle, 1000, ',' ); // Read & skip the header row

					while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== FALSE ) {
						// $post_data = array_combine( $header, $data );
						// Assign column data to vars
						$title = $data[0];
						$slug = $data[1];
						$usa_url = sanitize_text_field($data[2]);
						$ce_url = sanitize_text_field($data[3]);
						$base_url = sanitize_text_field($data[4]);	

						// Prepare post array for insertion
						$new_post = array(
							'post_title'   => sanitize_text_field( $title ),
							'post_type'    => 'ifudoc', // Your custom post type slug
							'post_name'    => sanitize_title( $slug ), // Set post slug
							'post_status'  => 'publish', 
						);

						// Insert the post
						$post_id = wp_insert_post( $new_post );

						if ( ! empty( $title ) ) {
							// Update metadata
							if ($post_id) {
								if ($usa_url) {
									$eng_usa_file = array(
										'code' => 'ENGUSA',
										'label' => 'English (USA Customers)',
										'active' => '1',
										'url_base' => dirname($usa_url),
										'filename' =>  basename($usa_url),
										);
									update_post_meta( $post_id, 'eng_usa_file_url', $usa_url );
									update_post_meta( $post_id, '_eng_usa_file_url_enabled', 1 );
									update_post_meta( $post_id, '_eng_usa_file', $eng_usa_file );
								}
								if ($ce_url) {
									$eng_ce_file = array(
										'code' => 'ENGCE',
										'label' => 'English (CE Customers)',
										'active' => '1',
										'url_base' => dirname($ce_url),
										'filename' =>  basename($ce_url),
										);
									update_post_meta( $post_id, 'eng_ce_file_url', $ce_url );
									update_post_meta( $post_id, '_eng_ce_file_url_enabled', 1 );
									update_post_meta( $post_id, '_eng_ce_file', $eng_ce_file );
								}
								if ($base_url) {
									update_post_meta( $post_id, 'base_lang_file_url', $base_url );
									update_post_meta( $post_id, '_base_lang_file_enabled', '1' );
								}
							}
							
							// Add more metadata fields as needed
						}
					}
					fclose( $handle );
					echo '<div class="notice notice-success"><p>CSV imported successfully!</p></div>';
				} else {
					echo '<div class="notice notice-error"><p>Error opening CSV file.</p></div>';
				}
			} else {
				echo '<div class="notice notice-error"><p>Please upload a valid CSV file.</p></div>';
			}
		}

       
}
?>