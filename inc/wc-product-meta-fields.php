<?php
/**
 * Product meta fields for document relationships
 */

namespace MGBdev\WC_Ifu_Docs;

/**
 * Add product documents meta box
 */
function add_product_documents_meta_box() {
    add_meta_box(
        'product_documents',
        __('Product Documents', 'wcifu-docs'),
        __NAMESPACE__ . '\\render_product_documents_meta_box',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', __NAMESPACE__ . '\\add_product_documents_meta_box');

/**
 * Render the product documents meta box
 */
function render_product_documents_meta_box($post) {
    wp_nonce_field('save_product_documents', 'product_documents_nonce');
    
    $selected_documents = get_post_meta($post->ID, '_product_documents', true);
    if (!is_array($selected_documents)) {
        $selected_documents = array();
    }
    
    $documents = get_posts(array(
        'post_type' => 'ifudoc',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    echo '<div class="product-documents-selector">';
    echo '<p><strong>' . __('Select documents for this product:', 'wcifu-docs') . '</strong></p>';
    
    foreach ($documents as $document) {
        $checked = in_array($document->ID, $selected_documents) ? 'checked' : '';
        echo '<label style="display: block; margin: 5px 0;">';
        echo '<input type="checkbox" name="product_documents[]" value="' . $document->ID . '" ' . $checked . '>';
        echo ' ' . esc_html($document->post_title);
        echo '</label>';
    }
    
    echo '</div>';
    
    // Add document display options
    echo '<h4>' . __('Display Options', 'wcifu-docs') . '</h4>';
    $display_location = get_post_meta($post->ID, '_documents_display_location', true);
    $locations = array(
        'tabs' => __('Product Tabs', 'wcifu-docs'),
        'description' => __('After Description', 'wcifu-docs'),
        'summary' => __('After Summary', 'wcifu-docs')
    );
    
    foreach ($locations as $key => $label) {
        $checked = ($display_location === $key) ? 'checked' : '';
        echo '<label style="display: block;">';
        echo '<input type="radio" name="documents_display_location" value="' . $key . '" ' . $checked . '>';
        echo ' ' . $label;
        echo '</label>';
    }
}

/**
 * Save product documents meta
 */
function save_product_documents_meta($post_id) {
    if (!isset($_POST['product_documents_nonce']) || 
        !wp_verify_nonce($_POST['product_documents_nonce'], 'save_product_documents')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $documents = isset($_POST['product_documents']) ? array_map('intval', $_POST['product_documents']) : array();
    update_post_meta($post_id, '_product_documents', $documents);
    
    $display_location = isset($_POST['documents_display_location']) ? sanitize_text_field($_POST['documents_display_location']) : 'tabs';
    update_post_meta($post_id, '_documents_display_location', $display_location);
}
add_action('save_post', __NAMESPACE__ . '\\save_product_documents_meta'); 