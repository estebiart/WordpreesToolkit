<?php 

function get_content_endpoint($data) {
    ob_start();
    
    $type = $data->get_param('type');
    $paged = $data->get_param('page');
    $departament = $data->get_param('departament');
    $city = $data->get_param('city');
    $order = $data->get_param('order');


    $args = array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => 9,
        'paged' => $paged, 
        'meta_query' => array(
            'relation' => 'AND',
        ),
    );


    if (!empty($departament) || !empty($city)) {

        
        if (!empty($departament)) {
            $args['meta_query'][] = array(
                'key' => 'locations_departament', 
                'value' => $departament,
                'compare' => 'LIKE', 
            );
        }

        if (!empty($city)) {
            $args['meta_query'][] = array(
                'key' => 'locations_city', 
                'value' => $city,
                'compare' => 'LIKE',
            );
        }
        $category = $data->get_param('project-categorie'); 
        if (!empty($category)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'project-categorie',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            );
        }

    $content_paged = new WP_Query($args);
   

    if ($content_paged->have_posts()) {
        ob_start();
        $base_path = dirname(__FILE__);
        $include_path = get_include_path_by_type($type, $base_path);
        include_once $include_path;
        $content = ob_get_clean();
        echo $content;
    }

    $html = ob_get_clean();
    $pagination = get_pagination_html($content_paged, $paged, $per_page);

    return new WP_REST_Response(array('html' => $html, 'pagination' => $pagination), 200);
    }else{
        if($type=="installations-container"){
            $postType = 'productos';
            $postCategory = 'product-category';
            $per_page = 12;
        }elseif($type=="news-container"){
            $postType = 'new';
            $postCategory = 'news-category';
            $per_page = 12;
        }elseif($type=="products-container"){
            $postType = 'productos';
            $postCategory =  'product-category';
            $per_page = 6;
        }elseif($type=="projects-container"){
            $postType = 'project';
            $postCategory = 'project-categorie'; 
            $per_page =9;
        }

        if ($type == "installations-container") {
            $base_args = array(
                'post_type' =>  $postType ,
                'post_status' => 'publish',
                'posts_per_page' => $per_page,
                'paged' => $paged,
                'meta_query' => array(
                    array(
                        'key' => 'installations_button', 
                        'compare' => 'EXISTS',
                    ),
                ),
            );
        }else{
            $base_args = array(
                'post_type' =>  $postType ,
                'post_status' => 'publish',
                'posts_per_page' => $per_page,
                'paged' => $paged,
                'orderby'        => 'title',
                'order'          => $order,
            );
        }

        $category_filter = $data->get_param($postCategory); 

        if (!empty($category_filter)) {
            $base_args['tax_query'] = array(
                array(
                    'taxonomy' =>$postCategory,
                    'field'    => 'slug',
                    'terms'    => $category_filter,
                ),
            );
        }


        $content_all = new WP_Query($base_args);
        $total_items_all = $content_all->found_posts;
        if ($total_items_all > $per_page) {
            $max_num_pages = max(1, ceil($total_items_all / $per_page));
            $paged = min($paged, $max_num_pages);
            $base_args['posts_per_page'] = $per_page;
            $base_args['paged'] = $paged;
            $content_paged = new WP_Query($base_args);
        } else {
            $content_paged = $content_all;
        }
        
        $total_products = $content_paged->found_posts;
        if ($content_paged->have_posts()) {
            ob_start();
            $base_path = dirname(__FILE__);
            $include_path = get_include_path_by_type($type, $base_path);
            include_once $include_path;
            $content = ob_get_clean();
            echo $content;
        }
        $html = ob_get_clean();
        $pagination = get_pagination_html($content_paged, $paged, $per_page);

        return new WP_REST_Response(array('html' => $html, 'pagination' => $pagination, 'total' => $total_products), 200);
    }

   
}
function get_include_path_by_type($type, $base_path) {
    switch ($type) {
        case "installations-container":
            return $base_path . '/../template-parts/installations-part.php';
        case "news-container":
            return $base_path . '/../template-parts/news-part.php';
        case "products-container":
            return $base_path . '/../template-parts/products-part.php';
        case "projects-container":
            return $base_path . '/../template-parts/projects-part.php';
        default:
            return '';
    }
}


function get_pagination_html($content_paged, $paged, $per_page) {

    if ($content_paged->found_posts <= $per_page) {
        return "";
    }
    ob_start();
    $pages = paginate_links(array(
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $content_paged->max_num_pages,
        'type'  => 'array',
        'prev_next' => false,
    ));
    
        if (is_array($pages)) {
            $max_num_pages_to_show = 5; 
            if ($paged > 1) {
                echo '<a href="#" class="sliderButton sliderButton--prev page-numbers" data-page="' . ($paged - 1) . '"></a>';
            } else {
                echo '<div class="sliderButton sliderButton--prev disabled"></div>';
            }
            

            $start_page = max(1, $paged - floor($max_num_pages_to_show / 2));
            $end_page = min($start_page + $max_num_pages_to_show - 1, $content_paged->max_num_pages);
    
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $paged) {
                    echo "<span class='page active' data-page='{$i}'>{$i}</span>";
                } else {
                    echo "<span class='page'><a href='#' class='page-numbers' data-page='{$i}'>$i</a></span>";
                }
            }
    
            if ($paged < $content_paged->max_num_pages) {
                echo '<a href="#" class="sliderButton sliderButton--next page-numbers" data-page="' . ($paged + 1) . '"></a>';
            } else {
                echo '<div class="sliderButton sliderButton--next disabled"></div>';
            }
        }
    return ob_get_clean();
}
