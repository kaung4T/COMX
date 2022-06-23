<?php //Category fields meta starts
if (!function_exists('newsup_taxonomy_add_new_meta_field')) :
// Add term page
    function newsup_taxonomy_add_new_meta_field()
    {
        // this will add the custom meta field to the add new term page

        $cat_color = array(
            'category-color-1' => __('Category Color 1', 'newsup'),
            'category-color-2' => __('Category Color 2', 'newsup'),
            'category-color-3' => __('Category Color 3', 'newsup'),
            'category-color-4' => __('Category Color 4', 'newsup'),

        );
        ?>
        <div class="form-field">
            <label for="term_meta[color_class_term_meta]"><?php _e('Color Class', 'newsup'); ?></label>
            <select id="term_meta[color_class_term_meta]" name="term_meta[color_class_term_meta]">
                <?php foreach ($cat_color as $key => $value): ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Select category color class. You can set appropriate categories color on "Categories" section of the theme customizer.', 'newsup'); ?></p>
        </div>
        <?php
    }
endif;
add_action('category_add_form_fields', 'newsup_taxonomy_add_new_meta_field', 10, 2);


if (!function_exists('newsup_taxonomy_edit_meta_field')) :
// Edit term page
    function newsup_taxonomy_edit_meta_field($term)
    {

        // put the term ID into a variable
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option("category_color_$t_id");

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label
                        for="term_meta[color_class_term_meta]"><?php _e('Color Class', 'newsup'); ?></label></th>
            <td>
                <?php
                $cat_color = array(
                    'category-color-1' => __('Category Color 1', 'newsup'),
                    'category-color-2' => __('Category Color 2', 'newsup'),
                    'category-color-3' => __('Category Color 3', 'newsup'),
                    'category-color-4' => __('Category Color 4', 'newsup'),
                );
                ?>
                <select id="term_meta[color_class_term_meta]" name="term_meta[color_class_term_meta]">
                    <?php foreach ($cat_color as $key => $value): ?>
                        <option value="<?php echo esc_attr($key); ?>"<?php selected($term_meta['color_class_term_meta'], $key); ?> ><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="description"><?php _e('Select category color class. You can set appropriate categories color on "Categories" section of the theme customizer.', 'newsup'); ?></p>
            </td>
        </tr>
        <?php
    }
endif;
add_action('category_edit_form_fields', 'newsup_taxonomy_edit_meta_field', 10, 2);




if (!function_exists('newsup_save_taxonomy_color_class_meta')) :
// Save extra taxonomy fields callback function.
    function newsup_save_taxonomy_color_class_meta($term_id)
    {
        if (isset($_POST['term_meta'])) {
            $t_id = $term_id;
            $term_meta = get_option("category_color_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            foreach ($cat_keys as $key) {
                if (isset ($_POST['term_meta'][$key])) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option("category_color_$t_id", $term_meta);
        }
    }

endif;
add_action('edited_category', 'newsup_save_taxonomy_color_class_meta', 10, 2);
add_action('create_category', 'newsup_save_taxonomy_color_class_meta', 10, 2);