<?php
/*
Plugin Name: Viva Customizer Cards
Description: A plugin to manage front page cards in the WordPress customizer and display them using a shortcode.
Version: 1.0
Author: Viva
*/

// Register Customizer Settings
function viva_customizer_cards_section($wp_customize) {
    $wp_customize->add_panel('viva_cards_panel', array(
        'title' => __('Front Page Cards', 'viva-theme'),
        'priority' => 30,
    ));

    $defaults = array(
        1 => array(
            'title' => 'Admissions',
            'desc' => 'Start your academic journey with us — discover endless possibilities.',
            'icon' => 'fas fa-file-alt',
            'button' => 'Apply Now',
            'link' => '#',
            'bg' => 'bg-blue-800 text-white',
        ),
        2 => array(
            'title' => 'Fees & Aid',
            'desc' => 'Learn how we make education affordable for every student.',
            'icon' => 'fas fa-wallet',
            'button' => 'Explore',
            'link' => '#',
            'bg' => 'bg-[#eeeeee] text-gray-800',
        ),
        3 => array(
            'title' => 'Student Portal',
            'desc' => 'Stay updated with your courses, grades, and campus news.',
            'icon' => 'fas fa-user-graduate',
            'button' => 'Login',
            'link' => '#',
            'bg' => 'bg-blue-800 text-white',
        ),
        4 => array(
            'title' => 'Academic Programs',
            'desc' => 'Choose from over 100 career-focused programs and disciplines.',
            'icon' => 'fas fa-graduation-cap',
            'button' => 'Learn More',
            'link' => '#',
            'bg' => 'bg-[#eeeeee] text-gray-800',
        ),
    );

    for ($i = 1; $i <= 4; $i++) {
        $section_id = "viva_card_section_$i";

        $wp_customize->add_section($section_id, array(
            'title' => __("Card $i", 'viva-theme'),
            'panel' => 'viva_cards_panel',
        ));

        $wp_customize->add_setting("card_title_$i", array('default' => $defaults[$i]['title']));
        $wp_customize->add_control("card_title_$i", array(
            'label' => __("Card Title", 'viva-theme'),
            'section' => $section_id,
            'type' => 'text',
        ));

        $wp_customize->add_setting("card_desc_$i", array('default' => $defaults[$i]['desc']));
        $wp_customize->add_control("card_desc_$i", array(
            'label' => __("Card Description", 'viva-theme'),
            'section' => $section_id,
            'type' => 'textarea',
        ));

        $wp_customize->add_setting("card_icon_$i", array('default' => $defaults[$i]['icon']));
        $wp_customize->add_control("card_icon_$i", array(
            'label' => __("Font Awesome Icon Class", 'viva-theme'),
            'section' => $section_id,
            'type' => 'text',
        ));

        $wp_customize->add_setting("card_button_text_$i", array('default' => $defaults[$i]['button']));
        $wp_customize->add_control("card_button_text_$i", array(
            'label' => __("Button Text", 'viva-theme'),
            'section' => $section_id,
            'type' => 'text',
        ));

        $wp_customize->add_setting("card_button_link_$i", array('default' => $defaults[$i]['link']));
        $wp_customize->add_control("card_button_link_$i", array(
            'label' => __("Button Link URL", 'viva-theme'),
            'section' => $section_id,
            'type' => 'url',
        ));

        $wp_customize->add_setting("card_bg_class_$i", array('default' => $defaults[$i]['bg']));
        $wp_customize->add_control("card_bg_class_$i", array(
            'label' => __("Card Background Tailwind Classes", 'viva-theme'),
            'section' => $section_id,
            'type' => 'text',
        ));
    }
}
add_action('customize_register', 'viva_customizer_cards_section');

// Render Cards HTML (reusable function)
function viva_render_cards() {
    ob_start();
    ?>
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <?php for ($i = 1; $i <= 4; $i++) :
                    $title = get_theme_mod("card_title_$i", '');
                    $desc = get_theme_mod("card_desc_$i", '');
                    $icon = get_theme_mod("card_icon_$i", '');
                    $button_text = get_theme_mod("card_button_text_$i", '');
                    $button_link = get_theme_mod("card_button_link_$i", '#');
                    $bg_class = get_theme_mod("card_bg_class_$i", 'bg-blue-800 text-white');
                ?>
                    <a href="<?php echo esc_url($button_link); ?>" class="relative group hover:scale-105 transition transform duration-300">
                        <div class="absolute -rotate-2 <?php echo esc_attr($bg_class); ?> w-full h-full rounded-xl top-3 left-3 z-0 shadow-lg"></div>
                        <div class="absolute -rotate-1 <?php echo esc_attr($bg_class); ?> w-full h-full rounded-xl top-1 left-1 z-0 shadow-md"></div>
                        <div class="relative <?php echo esc_attr($bg_class); ?> p-6 rounded-xl z-10 shadow-2xl border-4 border-white flex flex-col text-center h-full">
                            <i class="<?php echo esc_attr($icon); ?> text-4xl mb-4"></i>
                            <h3 class="text-lg font-bold mb-2"><?php echo esc_html($title); ?></h3>
                            <p class="mb-4"><?php echo esc_html($desc); ?></p>
                            <span class="mt-auto inline-block bg-white text-blue-800 font-semibold px-4 py-2 rounded-md">
                                <?php echo esc_html($button_text); ?>
                            </span>
                        </div>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// Display on front page
function viva_add_customizer_cards_to_front_page() {
    if (is_front_page()) {
        echo viva_render_cards();
    }
}
add_action('wp_footer', 'viva_add_customizer_cards_to_front_page');

// Register Shortcode
function viva_customizer_cards_shortcode($atts) {
    return viva_render_cards();
}
add_shortcode('viva_customizer_cards', 'viva_customizer_cards_shortcode');

// Enqueue Tailwind CSS and Font Awesome for use in frontend
function viva_enqueue_customizer_cards_assets() {
    if (!is_admin()) {
        wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', array(), '6.5.0');
        
        if (!wp_style_is('tailwindcss', 'enqueued')) {
            wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
        }
    }
}
add_action('wp_enqueue_scripts', 'viva_enqueue_customizer_cards_assets'); 
