<?php
/**
 * Custom single post template for IFU Documents
 */


namespace MGBdev\WC_Ifu_Docs;
//including utility functions
include_once (IFUD_GLOBAl_DIR . 'inc/util-functions.php');
include_once (IFUD_GLOBAl_DIR . 'inc/render-ifudoc.php');

get_header(); 

global $post;
setup_postdata($post);

?>
<header class="ifu-doc-header">
	<h1><?php echo esc_html__($post->post_title, 'wcifu-docs'); ?></h1>
	<p><?php echo esc_html__($post->post_content, 'wcifu-docs'); ?></p>
	
</header>
<div class="ifu-doc-wrapper">

    <?php
    // Get the ID of the current post
    $post_id = get_the_ID();
   
    // Retrieve all post meta data for the current post
	
	$eng_doc_single = array();
	$eng_usa = get_post_meta($post_id, '_eng_usa_file', true);
	$eng_ce = get_post_meta($post_id, '_eng_ce_file', true);
	$translations = get_post_meta($post_id, '_translations', true);
	$file_size = get_post_meta($post_id, '_document_file_size', true);

	$meta_data = get_post_meta( $post_id, '', false );

	if ($file_size) {
                    echo '<p>File size: ' . esc_html($file_size) . '</p>';
                }
	if ($translations || ($eng_usa && $eng_ce)) {
		echo '<h3>Please select the file download that corresponds to your language and / or region.</h3>';
	}
	?>
	<div id="wcifu-doc downloads">
	<?php
	if (!($eng_usa['url_base'] && $eng_ce['url_base'])) { //add single english download links
			if ($eng_usa) {
				$eng_doc_single = $eng_usa;
				$eng_doc_single['code'] = 'single';
			}
			elseif ($eng_ce) {
				$eng_doc_single = $eng_ce;
				$eng_doc_single['code'] = 'single';
			}
		echo wcifu_single_eng_download_button($eng_doc_single, $post_id);
		//add both usa & ce english download links	
		}
		else {
			echo wcifu_single_eng_download_button($eng_usa, $post_id);
			echo wcifu_single_eng_download_button($eng_ce, $post_id);
		
		}
		
        if ($translations) {
            echo wcifu_translations_download_button($translations, $post_id);
			
        }
	
    // Display the post meta data using var_dump()
    /*echo '<pre>';
    var_dump( $meta_data );
    echo '</pre>';
	echo '<h3> Field: _checked_lang </h3>';
	echo '<pre>';
	print_r(get_post_meta($post_id, '_checked_lang', true));
	echo '</pre>';
	echo '<h3> Field: _translations </h3>';
	echo '<pre>';
	print_r(get_post_meta($post_id, '_translations', true));echo '<pre>';*/
    ?>
	</div>
</div>

<?php get_footer(); ?> 