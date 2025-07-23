<?php
/**
 * WooCommerce Frontend display of product documents
 * 
 * @package WC_Ifu_Docs
 */
namespace MGBdev\WC_Ifu_Docs;


include_once (IFUD_GLOBAl_DIR . 'inc/wc-admin-settings.php');
include_once (IFUD_GLOBAl_DIR . 'inc/render-ifudoc.php');

/**
 * Add documents tab to product tabs
 */
function add_documents_product_tab($tabs) {
    global $product;
    
    $documents = get_post_meta($product->get_id(), '_product_documents', true);
    if (!empty($documents)) {
        $tabs['documents'] = array(
            'title' => __('IFU Documents', 'wcifu-docs'),
            'priority' => 25,
            'callback' => __NAMESPACE__ . '\\render_documents_tab_content'
        );
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', __NAMESPACE__ . '\\add_documents_product_tab');

/**
 * Render documents tab content
 */
function render_documents_tab_content() {
    global $product;
    
    $documents = get_post_meta($product->get_id(), '_product_documents', true);
    if (empty($documents)) {
        return;
    }
    
    echo '<div class="product-documents">';
    echo '<h3>' . __('Product IFU Documents', 'wcifu-docs') . '</h3>';
    echo '<div id="wcifu-doc downloads">';
    
    foreach ($documents as $doc_id) {
        $document = get_post($doc_id);
        if (!$document) continue;
        $eng_doc_single = array();
		$eng_usa = get_post_meta($doc_id, '_eng_usa_file', true);
		$eng_ce = get_post_meta($doc_id, '_eng_ce_file', true);
		$translations = get_post_meta($doc_id, '_translations', true);
		
        $file_size = get_post_meta($doc_id, '_document_file_size', true);
        ?>
			<div class="document-item">
				<h4><a href="<?php echo get_permalink($doc_id); ?>"><?php echo esc_html($document->post_title); ?></a></h4>		
		<?php if ($file_size) {
                    echo '<p>File size: ' . esc_html($file_size) . '</p>';
                }?>
		<?php
        if ($document->post_excerpt) {
            echo '<p>' . esc_html($document->post_excerpt) . '</p>';
        }
		if (!($eng_usa['url_base'] && $eng_ce['url_base'])) { //add single english download links
			if ($eng_usa) {
				$eng_doc_single = $eng_usa;
				$eng_doc_single['code'] = 'single';
			}
			elseif ($eng_ce) {
				$eng_doc_single = $eng_ce;
				$eng_doc_single['code'] = 'single';
			}
		echo wcifu_single_eng_download_button($eng_doc_single, $doc_id);
		//add both usa & ce english download links	
		}
		else {
			echo wcifu_single_eng_download_button($eng_usa, $doc_id);
			echo wcifu_single_eng_download_button($eng_ce, $doc_id);
		
		}
		
        if ($translations) {
            echo wcifu_translations_download_button($translations, $doc_id);
			
        }
        echo '</div>';
    }
    
    echo '</div></div>';
}