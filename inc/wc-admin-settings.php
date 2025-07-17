<?php
/**
 * WooCommerce settings integration
 * 
 * @package WC_Ifu_Docs
 */
 
namespace MGBdev\WC_Ifu_Docs;
 
 /**
 * Add settings tab to WooCommerce
 */
function add_documents_settings_tab($tabs) {
    $tabs['documents'] = __('Product Documents', 'wcifu-docs');
    return $tabs;
}
add_filter('woocommerce_settings_tabs_array', __NAMESPACE__ . '\\add_documents_settings_tab', 50);

/**
 * Settings tab content
 */
function documents_settings_tab_content() {
    woocommerce_admin_fields(get_documents_settings());
}
add_action('woocommerce_settings_documents', __NAMESPACE__ . '\\documents_settings_tab_content');

/**
 * Save settings
 */
function save_documents_settings() {
    woocommerce_update_options(get_documents_settings());
}
add_action('woocommerce_update_options_documents', __NAMESPACE__ . '\\save_documents_settings');

/**
 * Get settings array
 */
function get_documents_settings() {
    return array(
        'section_title' => array(
            'name' => __('Product Documents Settings', 'wcifu-docs'),
            'type' => 'title',
            'desc' => __('Configure how product documents are displayed and managed.', 'wcifu-docs'),
            'id' => 'wcifu_documents_section_title'
        ),
        'default_display_location' => array(
            'name' => __('Default Display Location', 'wcifu-docs'),
            'type' => 'select',
            'options' => array(
                'tabs' => __('Product Tabs', 'wcifu-docs'),
                'description' => __('After Description', 'wcifu-docs'),
                'summary' => __('After Summary', 'wcifu-docs')
            ),
            'id' => 'wcifu_default_display_location'
        ),
        'supported_languages' => array(
            'name' => __('Supported Non-English Languages', 'wcifu-docs'),
            'type' => 'textarea',
            'desc' => __('Enter one non-english language per line in the format CODE:Label (e.g., ESP:Español).', 'wcifu-docs'),
            'id' => 'wcifu_supported_languages',
            'css' => 'min-width:350px;height:120px;',
            'default' => "ESP:Español\nFRA:Français"
        ),
        'section_end' => array(
            'type' => 'sectionend',
            'id' => 'wcifu_documents_section_end'
        )
    );
}


