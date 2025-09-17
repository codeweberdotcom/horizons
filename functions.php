<?php

/**
 * horizons functions and definitions
 *
 * @package horizons
 */

add_action('wp_enqueue_scripts', 'horizons_enqueue_styles');
function horizons_enqueue_styles()
{
    wp_enqueue_style(
        'horizons-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('codeweber-style'),
        wp_get_theme()->get('Version')
    );
}

// Загрузка переводов
function horizons_theme_setup()
{
    // Загрузка переводов дочерней темы
    load_child_theme_textdomain('horizons', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'horizons_theme_setup'); // ← ДОБАВЬТЕ ЭТУ СТРОЧКУ!

// Подключение дополнительных файлов
$theme_includes = array(
    '/includes/metaboxes.php',
    '/includes/shortcodes.php',
    '/includes/custom-cpt.php',
    '/includes/add_image_sizes.php',
    '/includes/sidebars.php'
);

foreach ($theme_includes as $file) {
    $file_path = get_stylesheet_directory() . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

// Добавляем выпадающую кнопку в TinyMCE
function horizons_custom_mce_buttons($buttons)
{
    // Добавляем кнопку меню
    array_push($buttons, 'horizons_elements_menu');
    return $buttons;
}
add_filter('mce_buttons', 'horizons_custom_mce_buttons');

// Регистрируем TinyMCE plugin
function horizons_custom_mce_plugin($plugin_array)
{
    $plugin_array['horizons_mce'] = get_stylesheet_directory_uri() . '/js/mce-horizons-menu.js';
    return $plugin_array;
}
add_filter('mce_external_plugins', 'horizons_custom_mce_plugin');


// Подключаем скрипт для элементов Horizons
function horizons_elements_scripts()
{
    wp_enqueue_script(
        'horizons-elements',
        get_stylesheet_directory_uri() . '/js/horizons-elements.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'horizons_elements_scripts');



// Добавляем баннеры через хук
add_action('before_single_content', 'add_custom_single_banner', 10, 1);

function add_custom_single_banner($post_type)
{
    global $post;

    if ($post_type === 'staff') {

        // Получаем данные
        $name = get_post_meta($post->ID, '_staff_name', true);
        $surname = get_post_meta($post->ID, '_staff_surname', true);


        $full_position = get_post_meta($post->ID, '_staff_full_position', true);
        $regions = get_post_meta($post->ID, '_staff_regions', true);
        $short_description = get_post_meta($post->ID, '_staff_short_description', true);
        $language_skills = get_post_meta($post->ID, '_staff_language_skills', true);
        $email = get_post_meta($post->ID, '_staff_email', true);
        $phone = get_post_meta($post->ID, '_staff_phone', true);

        // Получаем thumbnail разными способами
        $thumbnail_url = '';
        if (has_post_thumbnail($post->ID)) {
            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'large');
        }

        // Запасное изображение
        $background_image = $thumbnail_url ?: get_template_directory_uri() . '/assets/images/default-staff.jpg';
?>

        <section class="staff-banner">
            <div class="col-12">
                <div class="row  wrapper g-0">
                    <div class="col-md-12 col-xl-5 ">
                        <div class="card h-100 overflow-hidden bg-dusty-navy">
                            <figure class="bg-cover wrapper image-wrapper bg-image h-100" data-image-src="<?php echo esc_url($background_image); ?>"> </figure>
                        </div>
                    </div>
                    <!--/column -->
                    <div class="col-12 col-xl-7 order-lg-2 ">
                        <div class="card h-100 bg-dusty-navy">
                            <div class="p-md-15 card-body align-content-center p-8">

                                <?php if (!empty($full_position)) : ?>
                                    <div class="text-line-before label-u mb-2 text-white">
                                        <?php echo esc_html($full_position); ?>
                                    </div>
                                <?php endif; ?>
                                <h1 class="h1 mb-1 text-white mt-md-7 mb-4">
                                    <?php echo $name; ?><span class="text-uppercase"> <?php echo $surname; ?></span>
                                    </h2>
                                    <?php if (!empty($short_description)) : ?>
                                        <blockquote class="icon body-l-r text-white mb-7">
                                            <p><?php echo esc_html($short_description); ?></p>
                                        </blockquote>
                                    <?php endif; ?>


                                    <?php if (!empty($language_skills) || !empty($regions)) : ?>
                                        <table class="w-100 text-white label-u mb-4">
                                            <tbody>
                                                <?php if (!empty($language_skills)) : ?>
                                                    <tr>
                                                        <td class="py-1 pe-3 align-text-top">
                                                            <div class="text-square-before">
                                                                <?php echo __('Languages', 'horizons') ?>:
                                                            </div>
                                                        </td>
                                                        <td class="py-1"><?php echo esc_html($language_skills); ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($regions)) : ?>
                                                    <tr>
                                                        <td class="py-1 pe-3 align-text-top">
                                                            <div class="text-square-before">
                                                                <?php echo __('Regions', 'horizons') ?>:
                                                            </div>
                                                        </td>
                                                        <td class="py-1">
                                                            <?php echo esc_html($regions); ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>

                                    <?php if (!empty($phone)) : ?>
                                        <div class="mt-0">
                                            <a href="tell:<?php echo esc_attr($phone); ?>"
                                                class="hover-4 link-body label-s text-white d-inline-flex align-items-center">
                                                <?php echo esc_html($phone); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($email)) : ?>
                                        <div class="mt-0">
                                            <a href="mailto:<?php echo esc_attr($email); ?>"
                                                class="hover-4 link-body label-s text-white d-inline-flex align-items-center">
                                                <?php echo esc_html($email); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                            </div>
                            <!--/column -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container -->
        </section>
<?php
    }
}
