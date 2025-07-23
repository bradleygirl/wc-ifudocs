<?php
/**
 * Functions to render IFU documents in parts acc to meta data
 *
 * @package WC_Ifu_Docs
 */

namespace MGBdev\WC_Ifu_Docs;

// Rendering download buttons for English documents

if (!function_exists('wcifu_single_eng_download_button')) {
	function wcifu_single_eng_download_button($arr, $doc_id, $file_size = '')
	{
		ob_start();
		if (!empty($arr['url_base'])) {
			if ($arr['code'] == 'single') {
				$arr['label'] = 'English';
			}
			?>
			<div class="english-dl" data-doc-id="<?php echo $doc_id; ?>"><span
					class="wcifu-button-title"><?php echo $arr['label']; ?></span>
				<a type="button" download="" data-fname="<?php echo $arr['filename']; ?>"
					data-folder="<?php echo $arr['url_base']; ?>" href="#" class="wcifu-download-link button" target="_blank"
					onmouseenter="setEngButtonUrl(this);">Download PDF</a>
				<?php
				if ($file_size) {
					echo ' - ' . esc_html($file_size);
				}
				?>
			</div>
		<?php
		} else {
			?>
			<div class="english-dl"> We're sorry; this file could not be loaded.</div>
			<?php
		}
		return ob_get_clean();
	}
}

// Rendering download buttons + menu for translated documents
if (!function_exists('wcifu_translations_download_button')) {
	function wcifu_translations_download_button($arr, $doc_id, $file_size = '')
	{
		ob_start();
		if (!empty($arr)) {
			$data_id = 'ifu_langdl-' . $doc_id;
			// depends on JS function setLangButtonUrl
			?>
			<div class="lang-dl" data-doc-id="<?php echo $doc_id; ?>"><span class="wcifu-button-title">Choose a translation: <select
						name="choose" data-id="<?php echo $data_id; ?>" class="inputLang" onchange="setLangButtonUrl(this);">
						<option value="" disabled="" selected="">Select language</option>
						<?php
						foreach ($arr as $id) {

							echo '<option data-file="' . $id['filename'] . '" value="' . $id['code'] . '">' . $id['label'] . '</option>';
						}
						?>
					</select></span>
				<a type="button" class="wcifu-download-link button" download="" id="<?php echo $data_id; ?>"
					data-folder="<?php echo $id['url_base']; ?>" href="#" target="_blank">Download PDF</a>
			</div>
			<?php
		} else { ?>
			<div class="lang-dl"> We're sorry; no translated files were found for this document.</div>
			<?php
		}
		return ob_get_clean();
	}
}

// Rendering SVG icons inline e.g. wcifu_render_svg('pdf-icon')

if (!function_exists('wcifu_render_svg')) {
	function wcifu_render_svg($icon_name)
	{
		$svg_path = IFUD_GLOBAl_DIR . 'assets/svg/' . $icon_name . '.svg';
		if (file_exists($svg_path)) {
			return file_get_contents($svg_path);
		}
	}
}