
<?php while ($content_paged->have_posts()) : $content_paged->the_post();

      $title = get_the_title();
      $url = get_permalink();
      $image_url = get_the_post_thumbnail_url();
      $location = get_field('locations');
      $terms = wp_get_post_terms(get_the_ID(), 'project-categorie');
      $category = !empty($terms) && !is_wp_error($terms) ? $terms[0]->name : '';
                ?>
                    <a href="<?php echo $url; ?>" class="project-card border-none mobile icon city" data-title="<?php echo $title; ?>" data-category="<?php echo $category; ?>" data-department="<?php echo $location['departament']; ?>" data-city="<?php echo $location['city']; ?>">
                        <div class="project-card__image prueb" data-aos="reveal-right" data-aos-offset="0" data-aos-anchor-placement="top-90%" data-aos-duration="900" data-aos-once="true">
                            <img src="<?php echo $image_url; ?>" alt="<?php echo get_the_title(); ?>">
                        </div>
                        <div class="project-card__wrapper">
                            <div class="project-card__wrapper__title">
                                <p class="project-card__tag"><?php echo $category; ?></p>
                                <p class="project-card__title"><?php echo get_the_title(); ?></p>
                            </div>
                            <div class="project-card__wrapper__location">
                                <p class="project-card__location">Ubicación:</p>
                                <p class="project-card__address"><?php echo $location['city'] . ', ' . $location['departament']; ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                    
