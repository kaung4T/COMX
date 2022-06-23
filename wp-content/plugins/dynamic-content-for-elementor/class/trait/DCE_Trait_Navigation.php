<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_Navigation {
    
    public static function dce_numeric_posts_nav() {

        if (is_singular())
            return;

        global $wp_query;
        //var_dump($wp_query->max_num_pages);
        /** Stop execution if there's only 1 page */
        if ($wp_query->max_num_pages <= 1)
            return;

        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        $max = intval($wp_query->max_num_pages);

        $prev_arrow = is_rtl() ? 'fa fa-angle-right' : 'fa fa-angle-left';
        $next_arrow = is_rtl() ? 'fa fa-angle-left' : 'fa fa-angle-right';

        /** Add current page to the array */
        if ($paged >= 1)
            $links[] = $paged;

        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (( $paged + 2 ) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<div class="navigation posts-navigation"><ul class="page-numbers">' . "\n";

        /** Previous Post Link */
        if (get_previous_posts_link())
            printf('<li>%s</li>' . "\n", get_previous_posts_link());

        /** Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $class = 1 == $paged ? ' class="current"' : '';

            printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');

            if (!in_array(2, $links))
                echo '<li>…</li>';
        }

        /** Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array) $links as $link) {
            $class = $paged == $link ? ' class="current"' : '';
            printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($link)), $link);
        }

        /** Link to last page, plus ellipses if necessary */
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links))
                echo '<li>…</li>' . "\n";

            $class = $paged == $max ? ' class="current"' : '';
            printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($max)), $max);
        }

        /** Next Post Link */
        if (get_next_posts_link())
            printf('<li>%s</li>' . "\n", get_next_posts_link());

        echo '</ul></div>' . "\n";
    }

    /* -------------------- */

    public static function get_wp_link_page($i) {
        if (!is_singular() || is_front_page()) {
            return get_pagenum_link($i);
        }

        // Based on wp-includes/post-template.php:957 `_wp_link_page`.
        global $wp_rewrite;
        $ggg = self::dce_dynamic_data();
        $post = get_post();
        $query_args = [];
        $url = get_permalink($ggg['id']);

        if ($i > 1) {
            if ('' === get_option('permalink_structure') || in_array($post->post_status, ['draft', 'pending'])) {
                $url = add_query_arg('page', $i, $url);
            } elseif (get_option('show_on_front') === 'page' && (int) get_option('page_on_front') === $post->ID) {
                $url = trailingslashit($url) . user_trailingslashit("$wp_rewrite->pagination_base/" . $i, 'single_paged');
            } else {
                $url = trailingslashit($url) . user_trailingslashit($i, 'single_paged');
            }
        }

        if (is_preview()) {
            if (( 'draft' !== $post->post_status ) && isset($_GET['preview_id'], $_GET['preview_nonce'])) {
                $query_args['preview_id'] = wp_unslash($_GET['preview_id']);
                $query_args['preview_nonce'] = wp_unslash($_GET['preview_nonce']);
            }

            $url = get_preview_post_link($post, $query_args, $url);
        }

        return $url;
    }

    /* --------------------- */

    public static function get_next_pagination() {
        //global $paged;
        $paged = max(1, get_query_var('paged'), get_query_var('page'));

        if (empty($paged))
            $paged = 1;

        $link_next = self::get_wp_link_page($paged + 1);

        return $link_next;
    }

    public static function numeric_query_pagination($pages, $settings) {

        $icon_prevnext = str_replace('right', '', $settings['pagination_icon_prevnext']);
        $icon_firstlast = str_replace('right', '', $settings['pagination_icon_firstlast']);

        $range = (int) $settings['pagination_range'] - 1; //la quantità di numeri visualizzati alla volta
        $showitems = ($range * 2) + 1;

        $paged = max(1, get_query_var('paged'), get_query_var('page'));

        if (empty($paged))
            $paged = 1;

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;

            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) {
            echo '<div class="dce-pagination">';

            //Progression
            if ($settings['pagination_show_progression'])
                echo '<span class="progression">' . $paged . ' / ' . $pages . '</span>';

            /* echo "<span>paged: ".$paged."</span>";
              echo "<span>range: ".$range."</span>";
              echo "<span>showitems: ".$showitems."</span>";
              echo "<span>pages: ".$pages."</span>"; */

            //First
            if ($settings['pagination_show_firstlast'])
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
                    echo '<a href="' . self::get_wp_link_page(1) . '" class="pagefirst"><i class="' . $icon_firstlast . 'left"></i> ' . __($settings['pagination_first_label'], 'dynamic-content-for-elementor' . '_texts') . '</a>';

            //Prev
            if ($settings['pagination_show_prevnext'])
                if ($paged > 1 && $showitems < $pages)
                    echo '<a href="' . self::get_wp_link_page($paged - 1) . '" class="pageprev"><i class="' . $icon_prevnext . 'left"></i> ' . __($settings['pagination_prev_label'], 'dynamic-content-for-elementor' . '_texts') . '</a>';

            //Numbers
            if ($settings['pagination_show_numbers'])
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                        echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . self::get_wp_link_page($i) . "' class=\"inactive\">" . $i . "</a>";
                    }
                }

            //Next
            if ($settings['pagination_show_prevnext'])
                if ($paged < $pages && $showitems < $pages)
                    echo '<a href="' . self::get_wp_link_page($paged + 1) . '" class="pagenext">' . __($settings['pagination_next_label'], 'dynamic-content-for-elementor' . '_texts') . ' <i class="' . $icon_prevnext . 'right"></i></a>';
            //Last
            if ($settings['pagination_show_firstlast'])
                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
                    echo '<a href="' . self::get_wp_link_page($pages) . '" class="pagelast">' . __($settings['pagination_last_label'], 'dynamic-content-for-elementor' . '_texts') . ' <i class="' . $icon_firstlast . 'right"></i></a>';

            echo '</div>';
        }
    }
    
}
