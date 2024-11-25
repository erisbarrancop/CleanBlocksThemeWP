<?php 
pageBanner(array(
  "title"=> "All Events",
  "subtitle"=> "See what we are up to.",
));
?>

    <div class="container container--narrow page-section">
      
      <?php 
        while(have_posts()){
            the_post(); 
            get_template_part('template-parts/content-event');
          }
        echo paginate_links();
      ?>
        
      <hr class="section-break">

          <p>Looking for a past event? <a href="<?php echo site_url('/past-events') ?>">Find them here!</a></p>

    </div>



