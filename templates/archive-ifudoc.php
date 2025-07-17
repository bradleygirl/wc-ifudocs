<?php

/**
 * Custom archive template for IFU Documents
 */

namespace MGBdev\WC_Ifu_Docs;
include_once (IFUD_GLOBAl_DIR . 'inc/render-ifudoc.php');
get_header(); 
?>

<div class="ifu-archive-wrapper">
    <header class="ifu-archive-header">
        <h1><?php echo esc_html__('IFU Documents', 'wcifu-docs'); ?></h1>
         <!-- <form class="ifu-archive-filter" method="get" action="">
          <input type="search" name="s" placeholder="<?php esc_attr_e('Search documents...', 'wcifu-docs'); ?>" value="<?php echo get_search_query(); ?>" /> 
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'wcifudoc_category',
                'hide_empty' => false,
            ));
            if ($categories && !is_wp_error($categories)) {
                echo '<select name="wcifudoc_category">';
                echo '<option value="">' . esc_html__('All Categories', 'wcifu-docs') . '</option>';
                foreach ($categories as $cat) {
                    $selected = (isset($_GET['wcifudoc_category']) && $_GET['wcifudoc_category'] == $cat->slug) ? 'selected' : '';
                    echo '<option value="' . esc_attr($cat->slug) . '" ' . $selected . '>' . esc_html($cat->name) . '</option>';
                }
                echo '</select>';
            }
            ?>
            <button type="submit"><?php esc_html_e('Filter', 'wcifu-docs'); ?></button>
        </form> -->
    </header>

    <?php
    // Hierarchical taxonomy rendering for IFU Documents
    $top_terms = get_terms(array(
        'taxonomy' => 'wcifudoc_category',
        'parent' => 0,
        'hide_empty' => false,
        'meta_query' => array(
            array(
                'key'   => '_wcifu_show_in_archive',
                'value' => 1,
                'compare' => '=',
            )),
    ));
    if ($top_terms && !is_wp_error($top_terms)) :
        foreach ($top_terms as $top_term) :
            // Get layer 2 children
            $child_terms = get_terms(array(
                'taxonomy' => 'wcifudoc_category',
                'parent' => $top_term->term_id,
                'hide_empty' => false,
            ));
            // Only show parent if it has children with posts
            $has_child_with_posts = false;
            if ($child_terms && !is_wp_error($child_terms)) {
                foreach ($child_terms as $child_term) {
                    $child_query = new \WP_Query(array(
                        'post_type' => 'ifudoc',
                        'tax_query' => array(array(
                            'taxonomy' => 'wcifudoc_category',
                            'field' => 'term_id',
                            'terms' => $child_term->term_id,
                            'meta_query' => array(
                                array(
                                    'key'   => '_wcifu_show_in_archive',
                                    'value' => 1,
                                    'compare' => '=',
                                )),
                        )),
                        'posts_per_page' => -1,
                    ));
                    if ($child_query->have_posts()) {
                        $has_child_with_posts = true;
                        break;
                    }
                }
            }
            if (!$has_child_with_posts) continue;
            ?>
            <section class="ifu-category-group" aria-labelledby="cat-<?php echo esc_attr($top_term->term_id); ?>">
                <h2 id="cat-<?php echo esc_attr($top_term->term_id); ?>"><?php echo esc_html($top_term->name); ?></h2>
                <?php
                if ($child_terms && !is_wp_error($child_terms)) :
                    foreach ($child_terms as $child_term) :
                        $child_query = new \WP_Query(array(
                            'post_type' => 'ifudoc',
                            'tax_query' => array(array(
                                'taxonomy' => 'wcifudoc_category',
                                'field' => 'term_id',
                                'terms' => $child_term->term_id,
                            )),
                            'posts_per_page' => -1,
                        ));
                        if (!$child_query->have_posts()) continue;
                        ?>
                        <div class="ifu-category-child-group" aria-labelledby="child-<?php echo esc_attr($child_term->term_id); ?>">
                            <h3 id="child-<?php echo esc_attr($child_term->term_id); ?>"><?php echo esc_html($child_term->name); ?></h3>
                            <div class="ifu-documents-cards" role="list">
                                <?php while ($child_query->have_posts()) : $child_query->the_post(); ?>
                                    <div class="ifu-document-card" role="listitem">
                                        <h4 class="ifu-document-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <div class="ifu-document-excerpt"><?php the_excerpt(); ?></div>
                                       <!-- <div class="ifu-document-meta">
                                            <?php
											$doc_id = get_the_ID();
                                            $file_size = get_post_meta($doc_id, '_document_file_size', true);
                                            if ($file_size) {
                                                echo '<span class="ifu-file-size">' . esc_html($file_size) . '</span>';
                                            }
                                            
											$eng_doc_single = array();
											$eng_usa = get_post_meta($doc_id, '_eng_usa_file', true);
											$eng_ce = get_post_meta($doc_id, '_eng_ce_file', true);
											$translations = get_post_meta($doc_id, '_translations', true);
											
											// Download links for each English language file

                                            if (!($eng_usa['url_base'] && $eng_ce['url_base'])) { //add single english download links
												if ($eng_usa) {
													$eng_doc_single = $eng_usa;
													$eng_doc_single['code'] = 'single';
												}
												elseif ($eng_ce) {
													$eng_doc_single = $eng_ce;
													$eng_doc_single['code'] = 'single';
												}
												else {
													?>
													<div class="english-dl"> We're sorry; this file could not be loaded.</div>
													<?php
												}
											echo wcifu_single_eng_download_button($eng_doc_single, $doc_id);
											//add both usa & ce english download links	
											}
											else {
												echo wcifu_single_eng_download_button($eng_usa, $doc_id);
												echo wcifu_single_eng_download_button($eng_ce, $doc_id);
											
											}
		
                                            // Other languages
                                            if ($translations) {
												echo wcifu_translations_download_button($translations, $doc_id);												
											}
											else { ?>
												<div class="lang-dl"> We're sorry; no translated files were found for this document.</div>
											<?php
											}
                                            echo '</ul>';
                                            ?>
                                        </div>-->
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endforeach;
                endif;
                ?>
            </section>
        <?php endforeach;
    else : ?>
        <p><?php esc_html_e('No IFU Document categories found.', 'wcifu-docs'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?> 