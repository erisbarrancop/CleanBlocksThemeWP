<?php
        pageBanner();
        ?>

    <div class="container container--narrow page-section">
    
    <div class="generic-content">
        <div class="row group">

            <div class="one-third">
                <?php the_post_thumbnail('professorPortrait'); ?>
            </div>

            <div class="two-thirds">
                <?php 
                    $likeCount = new WP_Query(array(
                        'post_type'=> 'like',
                        'meta_query'=> array(
                            array(
                                'key'=> 'liked_professor_id',
                                'compare'=> '=',
                                'value' => get_the_ID()
                            )
                        )
                    ));


                    $existStatus = 'no';

                    if(is_user_logged_in()){
                        $existQuery = new WP_Query(array(
                            'author' => get_current_user_id(),
                            'post_type'=> 'like',
                            'meta_query'=> array(
                                array(
                                    'key'=> 'liked_professor_id',
                                    'compare'=> '=',
                                    'value' => get_the_ID()
                                )
                            )
                        ));
    
                        if($existQuery->found_posts){
                            $existStatus = 'yes';
                        }
                    }

                    

                ?>
                <span class="like-box" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
                    <i class="fa-solid fa-heart-circle-plus" aria-hidden="true"></i>
                    <i class="fa-solid fa-heart-circle-minus" aria-hidden="true"></i>
                    <span class="like-count"><?php echo $likeCount->found_posts;?></span>
                </span>
                <?php the_content(); ?>
            </div>

        </div>
    </div>  
        
        <?php 
            
            $relatedPrograms = get_field('related_programs');
            
            if($relatedPrograms){
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
                echo '<ul class="link-list min-list">';
                foreach ($relatedPrograms as $program ) { ?>
                    <li><a href="<?php echo get_the_permalink($program);?>"><?php echo get_the_title($program); ?></a></li>
                <?php }
                echo '</ul>';
            }else{
                echo '<hr class="section-break">';
                echo '<p class="headline headline--small">There are no related Programs for this Professor</p>';
            }
        ?>
        
    </div>
    
