<?php
/**
 * Utility functions 
 *
 * @package WC_Ifu_Docs
 */

namespace MGBdev\WC_Ifu_Docs;

// Helper function to get supported languages from Woocommerce admin settings as an array
if (!function_exists('wcifu_get_supported_languages')) {
function wcifu_get_supported_languages() {
    $raw = get_option('wcifu_supported_languages', "ESP:Spanish\nFRA:French\nDEU:German");
    $lines = preg_split('/\r?\n/', $raw);
    $langs = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if (!$line) continue;
        if (preg_match('/^([A-Z]{3}):(.+)$/i', $line, $m)) {
            // $langs[strtoupper($m[1])] = trim($m[2]);
			$langs[] = array(
				'code' => strtoupper($m[1]),
				'label' => trim($m[2])
			);
        }
    }
    return $langs;
}
}



