<?php
function your_theme_scripts() {
     wp_enqueue_script('axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', array(), null, true);
}
function custom_script_shortcode() {
    return '<script src="' . get_template_directory_uri() . '/sources/js/components/pagination.js"></script>';
}
add_shortcode('custom_script', 'custom_script_shortcode');

add_action('wp_enqueue_scripts', 'your_theme_scripts');




add_action('rest_api_init', function () {
    register_rest_route('your_namespace/v1', '/content', array(
        'methods' => 'GET',
        'callback' => 'get_content_endpoint',
    ));
});
add_filter('wpcf7_validate_text*', 'custom_name_validation_filter', 20, 2);

function custom_name_validation_filter($result, $tag)
{
    if ('user' == $tag->name) {
        $pattern = '/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/';
        if (!preg_match($pattern, $_POST['user'])) {
            $result->invalidate($tag, "Ingresa solo letras y espacios en el campo Nombre.");
        }
    }

    return $result;
} ?>