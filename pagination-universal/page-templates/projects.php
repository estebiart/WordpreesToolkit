<?php
/*=================
Template Name: PROJECTS
===================*/
get_header('wordpress');
$current_term = get_queried_object();
$current_term_id = $current_term->term_id;
$pageId = get_the_ID();

$mainTitle = get_field('main_title', $pageId);
$description = get_field('description', $pageId);
$closingData = get_field('closing_section', $pageId);
$button_text = get_field('button_text', $pageId);

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'post_type' => 'project',
    'post_status' => 'publish',
    'posts_per_page' => -1,
);
$categories = get_terms(array(
    'taxonomy' => 'project-categorie',
    'hide_empty' => false,
));

if ($categories && !is_wp_error($categories)) {
    foreach ($categories as $category) {
        $category_projects_count = new WP_Query(array(
            'post_type' => 'project',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'project-categorie',
                    'field' => 'term_id',
                    'terms' => $category->term_id
                )
            )
        ));
        if ($category_projects_count->have_posts()) {
            $uniqueCategories[] = $category->name;
        }
        wp_reset_postdata();
    }
}

$projects = new WP_Query($args);
$latestProjects = array(); 
$counter = 0;

$departments = array();
$cities = array();

if ($projects->have_posts()) :
    while ($projects->have_posts()) : $projects->the_post();
        $locations = get_field('locations');
        if ($locations) {
            $cities[] = $locations['city'];
            $departments[] = $locations['departament'];
            if ($counter >= $projects->post_count - 3) {
                $url = get_permalink();
                $latestProjects[] = array(
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'image' => get_the_post_thumbnail_url(),
                    'city' => $locations['city'],
                    'departament' => $locations['departament'],
                    'url' => $url,
                );
            }

            $counter++;
        }
    endwhile;
    wp_reset_postdata();
endif;

$uniqueCities = array_unique($cities);

$uniquedepartments = array_unique($departments);


?>
<div id="currentCategorySlug" data-slug="<?php echo $current_term ? esc_attr($current_term->name) : 'default-category'; ?>"></div>

<div class="section breadcrum" >
    <div class="container" data-aos="reveal-up"  data-aos-anchor-placement="top-bottom" data-aos-once="true">
        <p class="breadcrum__wrapper">
            <?php
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('</p><p id=“breadcrumbs” class="breadcrum__wrapper" data-aos="reveal-up">', '</p><p>');
            }
            ?>
        </p>
    </div>
</div>

<section class="project-banner" data-aos="reveal-up">
    <div class="container">
        <div class="main-banner main-banner-projects">
            <div class="main-banner__image-slider" data-aos="reveal-right" data-aos-duration="1100">
                <div class="image-slider">
                    <div class="swiper-wrapper">
                    <?php foreach ($latestProjects as $project) : ?>
                        <?php 
                            $image_url = $project['image'];
                        ?>
                        <div class="swiper-slide">
                            <div class="swiper-slide-inner" data-swiper-parallax="50%">
                                <img class="swiper-lazy" src="<?php echo esc_url($image_url); ?>" draggable="false" />
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="main-banner__quote-slider" id="banner-quote-slider">
                <div class="quote-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($latestProjects as $project) : ?>
                            <?php 
                                $location = get_field('locations', get_page_by_title($project['title'])->ID);
                            ?>
                            <div class="swiper-slide">
                                <div class="quote-slider__wrapper">
                                    <div class="quote-slider__wrapper__texts">
                                        <div class="clippath">
                                            <p class="quote-slider__title"><?php echo esc_html($project['title']); ?></p>
                                            <p class="quote-slider__subtitle">Ubicación:</p>
                                            <p class="quote-slider__location"><?php echo esc_html($project['city']) . ', ' . esc_html($project['departament']); ?></p>
                                        </div>
                                    </div>
                                    <div class="quote-slider__button">
                                        <a href="<?php echo esc_url($project['url']); ?>" class="button blue icon">
                                            <p><?php echo esc_html($button_text); ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="main-banner__quote-slider__buttons">
                    <div id="main_banner_slider_prev" class="sliderButton sliderButton--prev"></div>
                    <div id="main_banner_slider_next" class="sliderButton sliderButton--next"></div>
                </div>
                <div id="main_banner_pagination" class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>


<section class="information" id="projects-information" data-aos="fade-up"  data-aos-anchor-placement="top-center" data-aos-duration="500" data-aos-once="true">
    <div class="container">
        <h1 class="information__title"><?php echo $mainTitle ?></h1>
        <div class="information__wrapper">
            <p class="information__description"><?php echo $description ?></p>
        </div>
    </div>
</section>

<section class="projects" >
    <div class="container">
        <div class="projects__order" data-aos="fade-up"  data-aos-anchor-placement="top-bottom" data-aos-once="true">
            <div class="projects__order__button">
                <p>Ordenar por:<span id="order-project">A - Z</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.99953 10.6665C7.84376 10.6668 7.69281 10.6126 7.57287 10.5132L3.57287 7.17986C3.43672 7.0667 3.35111 6.90409 3.33485 6.72781C3.3186 6.55152 3.37304 6.376 3.4862 6.23986C3.59936 6.10371 3.76197 6.0181 3.93825 6.00184C4.11453 5.98559 4.29005 6.04003 4.4262 6.15319L7.99953 9.13986L11.5729 6.25986C11.6411 6.20448 11.7195 6.16313 11.8037 6.13817C11.888 6.11322 11.9763 6.10516 12.0637 6.11445C12.151 6.12374 12.2357 6.15021 12.3127 6.19232C12.3898 6.23444 12.4578 6.29137 12.5129 6.35986C12.5739 6.42841 12.6202 6.50882 12.6487 6.59607C12.6772 6.68332 12.6874 6.77552 12.6787 6.8669C12.6699 6.95827 12.6424 7.04685 12.5978 7.12709C12.5532 7.20733 12.4925 7.2775 12.4195 7.33319L8.41953 10.5532C8.29614 10.6369 8.14827 10.6768 7.99953 10.6665Z" fill="#4A4A49" />
                    </svg>
                </p>
            </div>
            <div class="projects__order__wrapper">
                <p id="order-az">A - Z</p>
                <p id="order-za">Z - A</p>
            </div>
        </div>
        <div class="projects__wrapper" >
            <div class="projects__filter">
                <div id="filter_control" class="projects__filter__button">
                    <p>Filtros</p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M28.1905 9.14332H11.4286M8.381 9.14332H3.80957M28.1905 24.3814H11.4286M8.381 24.3814H3.80957M20.5715 16.7624H3.80957M28.1905 16.7624H23.6191M9.90481 6.0957C10.3089 6.0957 10.6965 6.25625 10.9823 6.54202C11.2681 6.82779 11.4286 7.21537 11.4286 7.61951V10.6671C11.4286 11.0713 11.2681 11.4589 10.9823 11.7446C10.6965 12.0304 10.3089 12.1909 9.90481 12.1909C9.50067 12.1909 9.11308 12.0304 8.82731 11.7446C8.54154 11.4589 8.381 11.0713 8.381 10.6671V7.61951C8.381 7.21537 8.54154 6.82779 8.82731 6.54202C9.11308 6.25625 9.50067 6.0957 9.90481 6.0957ZM9.90481 21.3338C10.3089 21.3338 10.6965 21.4943 10.9823 21.7801C11.2681 22.0659 11.4286 22.4535 11.4286 22.8576V25.9052C11.4286 26.3094 11.2681 26.697 10.9823 26.9827C10.6965 27.2685 10.3089 27.429 9.90481 27.429C9.50067 27.429 9.11308 27.2685 8.82731 26.9827C8.54154 26.697 8.381 26.3094 8.381 25.9052V22.8576C8.381 22.4535 8.54154 22.0659 8.82731 21.7801C9.11308 21.4943 9.50067 21.3338 9.90481 21.3338ZM22.0953 13.7148C22.4994 13.7148 22.887 13.8753 23.1728 14.1611C23.4586 14.4468 23.6191 14.8344 23.6191 15.2386V18.2862C23.6191 18.6903 23.4586 19.0779 23.1728 19.3637C22.887 19.6494 22.4994 19.81 22.0953 19.81C21.6911 19.81 21.3036 19.6494 21.0178 19.3637C20.732 19.0779 20.5715 18.6903 20.5715 18.2862V15.2386C20.5715 14.8344 20.732 14.4468 21.0178 14.1611C21.3036 13.8753 21.6911 13.7148 22.0953 13.7148Z" stroke="#055C94" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="projects__filter__menu">
                    <div class="filter__clean">
                        <div class="button outline" id="btn-clean-filters">
                            <p>Limpiar filtros</p>
                        </div>
                        <div id="filter_control" class="filter__clean__close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <path d="M8.33301 8.33301L19.9997 19.9997M19.9997 19.9997L31.6663 8.33301M19.9997 19.9997L8.33301 31.6663M19.9997 19.9997L31.6663 31.6663" stroke="#F5F5F5" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="filter__accordion">
                        <div id="accordion" class="filter__accordion__title">
                            <p>Categorías</p>
                            <svg  class="rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.01512 6.00047C8.17089 6.00016 8.32184 6.05441 8.44178 6.1538L12.4418 9.48713C12.5779 9.60029 12.6635 9.7629 12.6798 9.93918C12.696 10.1155 12.6416 10.291 12.5284 10.4271C12.4153 10.5633 12.2527 10.6489 12.0764 10.6651C11.9001 10.6814 11.7246 10.627 11.5884 10.5138L8.01512 7.52713L4.44178 10.4071C4.37359 10.4625 4.29513 10.5039 4.2109 10.5288C4.12668 10.5538 4.03835 10.5618 3.951 10.5525C3.86364 10.5433 3.77899 10.5168 3.7019 10.4747C3.62481 10.4326 3.5568 10.3756 3.50178 10.3071C3.44073 10.2386 3.39449 10.1582 3.36596 10.0709C3.33743 9.98367 3.32723 9.89147 3.33599 9.80009C3.34476 9.70872 3.37229 9.62014 3.41688 9.5399C3.46147 9.45966 3.52215 9.3895 3.59512 9.3338L7.59512 6.1138C7.71851 6.03012 7.86638 5.99022 8.01512 6.00047Z" fill="#4A4A49" />
                            </svg>
                        </div>
                        <div class="filter__accordion__wrapper flex" id="filter-projects">
                            <div class="selector item active " data-category="Todos">
                                <div class="selector__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 14 14" fill="none">
                                        <path d="M3.32634 7.69451L6.41556 9.85761L10.7417 3.67917" stroke="#0573BA" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="selector__text">Todos</p>
                            </div>
                            <?php
                            foreach ($uniqueCategories as $category) :
                            ?>
                                <div class="selector item" data-category="<?php echo $category ?>">
                                    <div class="selector__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 14 14" fill="none">
                                            <path d="M3.32634 7.69451L6.41556 9.85761L10.7417 3.67917" stroke="#0573BA" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <p class="selector__text"><?php echo $category ?></p>
                                </div>
                            <?php
                            endforeach;
                            ?>

                        </div>
                    </div>
                    <div class="filter__accordion">
                        <div id="accordion" class="filter__accordion__title">
                            <p>Departamento</p>
                            <svg class="rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.01512 6.00047C8.17089 6.00016 8.32184 6.05441 8.44178 6.1538L12.4418 9.48713C12.5779 9.60029 12.6635 9.7629 12.6798 9.93918C12.696 10.1155 12.6416 10.291 12.5284 10.4271C12.4153 10.5633 12.2527 10.6489 12.0764 10.6651C11.9001 10.6814 11.7246 10.627 11.5884 10.5138L8.01512 7.52713L4.44178 10.4071C4.37359 10.4625 4.29513 10.5039 4.2109 10.5288C4.12668 10.5538 4.03835 10.5618 3.951 10.5525C3.86364 10.5433 3.77899 10.5168 3.7019 10.4747C3.62481 10.4326 3.5568 10.3756 3.50178 10.3071C3.44073 10.2386 3.39449 10.1582 3.36596 10.0709C3.33743 9.98367 3.32723 9.89147 3.33599 9.80009C3.34476 9.70872 3.37229 9.62014 3.41688 9.5399C3.46147 9.45966 3.52215 9.3895 3.59512 9.3338L7.59512 6.1138C7.71851 6.03012 7.86638 5.99022 8.01512 6.00047Z" fill="#4A4A49" />
                            </svg>
                        </div>
                        <div class="filter__accordion__wrapper" id="filter-deparments" data-slug="">
                            <?php foreach ($uniquedepartments as $department) : ?>
                                <div class="filter__accordion__wrapper--item item2">
                                    <input id="<?php echo $department ?>" type="checkbox" />
                                    <label for="<?php echo $department ?>">
                                        <span></span>
                                        <?php echo $department ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="filter__accordion">
                        <div id="accordion" class="filter__accordion__title">
                            <p>Ciudad</p>
                            <svg class="rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.01512 6.00047C8.17089 6.00016 8.32184 6.05441 8.44178 6.1538L12.4418 9.48713C12.5779 9.60029 12.6635 9.7629 12.6798 9.93918C12.696 10.1155 12.6416 10.291 12.5284 10.4271C12.4153 10.5633 12.2527 10.6489 12.0764 10.6651C11.9001 10.6814 11.7246 10.627 11.5884 10.5138L8.01512 7.52713L4.44178 10.4071C4.37359 10.4625 4.29513 10.5039 4.2109 10.5288C4.12668 10.5538 4.03835 10.5618 3.951 10.5525C3.86364 10.5433 3.77899 10.5168 3.7019 10.4747C3.62481 10.4326 3.5568 10.3756 3.50178 10.3071C3.44073 10.2386 3.39449 10.1582 3.36596 10.0709C3.33743 9.98367 3.32723 9.89147 3.33599 9.80009C3.34476 9.70872 3.37229 9.62014 3.41688 9.5399C3.46147 9.45966 3.52215 9.3895 3.59512 9.3338L7.59512 6.1138C7.71851 6.03012 7.86638 5.99022 8.01512 6.00047Z" fill="#4A4A49" />
                            </svg>
                        </div>
                        <div class="filter__accordion__wrapper" id="filter-cities" data-slug="">

                            <?php  foreach ($uniqueCities as $city) : ?>
                                <div class="filter__accordion__wrapper--item item3">
                                    <input id="<?php echo $city ?>" type="checkbox" />
                                    <label for="<?php echo $city ?>">
                                        <span></span>
                                        <?php echo $city ?>
                                    </label>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display:none">
            <?php while ($projects->have_posts()) : $projects->the_post();
            $location = get_field('locations');
            $terms = wp_get_post_terms(get_the_ID(), 'project-categorie');
            $category = !empty($terms) && !is_wp_error($terms) ? $terms[0]->name : '';
                    ?>
              <a class="project-card-hidden" data-title="<?php echo $title; ?>" data-category="<?php echo $category; ?>" data-department="<?php echo $location['departament']; ?>" data-city="<?php echo $location['city']; ?>">
              </a>
              <?php endwhile; ?>
            </div>
            <div class="projects__cards pagination-container" id="projects-container"> </div>
                  
            </div>
            <div id="pagination-container" class="projects__cards__paginator"></div>


    <?php wp_reset_postdata(); ?>

        </div>
    </div>
</section>



<?php get_footer(); ?>