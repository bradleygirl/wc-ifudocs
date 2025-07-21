<?php
/**
 * Plugin Name:     IFU and Product Documents for Woocommerce
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          MGB Dev
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wcifu-docs
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         WC_Ifu_Docs
 */

// Your code starts here.
namespace MGBdev\WC_Ifu_Docs;

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

define('IFUD_GLOBAl_VERSION', '0.1.1');
define('IFUD_GLOBAl_NAME', 'wcifud-global');
define('IFUD_GLOBAl_ABSPATH', __DIR__);
define('IFUD_GLOBAl_BASE_NAME', plugin_basename(__FILE__));
define('IFUD_GLOBAl_DIR', plugin_dir_path(__FILE__));
define('IFUD_GLOBAl_URL', plugin_dir_url(__FILE__));

include_once(IFUD_GLOBAl_DIR . 'inc/util-functions.php');
require_once(IFUD_GLOBAl_DIR . 'inc/register-post-type.php');
include(IFUD_GLOBAl_DIR . 'inc/register-post-meta.php');

add_action('before_woocommerce_init', function () {
    // Check if the FeaturesUtil class exists in the \Automattic\WooCommerce\Utilities namespace.
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        // Declare compatibility with custom order tables using the FeaturesUtil class.
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

/**
 * Check if WooCommerce is active and load dependent files
 */
function wcifu_maybe_load_woocommerce_features()
{
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>';
            echo __('IFU and Product Documents for Woocommerce requires WooCommerce to be installed and active.', 'wcifu-docs');
            echo '</p></div>';
        });
        return;
    }
    // Load additional WooCommerce-specific files
    require_once(IFUD_GLOBAl_DIR . 'inc/wc-product-meta-fields.php');
    require_once(IFUD_GLOBAl_DIR . 'inc/wcifu-taxonomy.php');
    require_once(IFUD_GLOBAl_DIR . 'inc/products-frontend.php');
    require_once(IFUD_GLOBAl_DIR . 'inc/wc-admin-settings.php');
}
add_action('plugins_loaded', 'MGBdev\\WC_Ifu_Docs\\wcifu_maybe_load_woocommerce_features', 20);


// Force use of plugin archive template for IFU Documents

add_filter('single_template', 'MGBdev\\WC_Ifu_Docs\\get_ifudoc_template');

function get_ifudoc_template($single_template)
{
    global $post;

    if (is_singular('ifudoc')) {
        //$archive_template = dirname( __FILE__ ) . '/post-type-template.php';
        $single_template = IFUD_GLOBAl_DIR . '/templates/single-ifudoc.php';
        if (file_exists($single_template)) {
            $template =  $single_template;
        }
    }
    return $template;
}

add_filter('archive_template', 'MGBdev\\WC_Ifu_Docs\\get_ifudoc_archive_template');

function get_ifudoc_archive_template($archive_template)
{
    global $post;

    if (is_post_type_archive('ifudoc')) {
        //$archive_template = dirname( __FILE__ ) . '/post-type-template.php';
        $archive_template = IFUD_GLOBAl_DIR . '/templates/archive-ifudoc.php';
        if (file_exists($archive_template)) {
            $template = $archive_template;
        }
    }
    return $template;
}


add_action('wp_enqueue_scripts', 'MGBdev\\WC_Ifu_Docs\\wcifu_doc_download_js');

if (! function_exists('MGBdev\\WC_Ifu_Docs\\wcifu_doc_download_js')) {
	function wcifu_doc_download_js() {
		wp_register_script(
			'wcifu-lang-dl',
			IFUD_GLOBAl_URL . 'js/wcifu-lang-dl.js',
			array(),
			// don't add array for dependencies if there are none
			'0.1.1',
			array(
				'strategy' => 'defer',
				true,
			),
		);
		wp_enqueue_script('wcifu-lang-dl');
	}
}


add_action('wp_enqueue_scripts', 'MGBdev\\WC_Ifu_Docs\\wcifu_doc_styles');


if (! function_exists('MGBdev\\WC_Ifu_Docs\\wcifu_doc_styles')) {
	function wcifu_doc_styles() {
		if (is_product()) {
			wp_enqueue_style('wcifu-product-tab-styles', IFUD_GLOBAl_URL . 'assets/css/product-tab.css', array(), '0.1.1', 'all');
		}
		if (is_singular('ifudoc')) {
			wp_enqueue_style('wcifu-ifudoc-styles', IFUD_GLOBAl_URL . 'assets/css/ifudoc.css', array(), '0.1.1', 'all');
		}
        if (is_archive('ifudoc')) {
			wp_enqueue_style('wcifu-ifudoc-archive-styles', IFUD_GLOBAl_URL . 'assets/css/archive-ifudoc.css', array(), '0.1.1', 'all');
		}
	}
}
