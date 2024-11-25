<?php
        pageBanner();
        ?>
        

    <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a> 
        <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>
        <div class="generic-content"><?php the_content();?></div>  
        
        <?php 
            
            $relatedPrograms = get_field('related_programs');
            
            if($relatedPrograms){
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Related Programs to the Event</h2>';
                echo '<ul class="link-list min-list">';
                foreach ($relatedPrograms as $program ) { ?>
                    <li><a href="<?php echo get_the_permalink($program);?>"><?php echo get_the_title($program); ?></a></li>
                <?php }
                echo '</ul>';
            }else{
                echo '<hr class="section-break">';
                echo '<p class="headline headline--small">There are no related Programs for this Event</p>';
            }
        ?>
        
    </div>
    
  