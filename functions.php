<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');
function testproject_custom_rest(){
    register_rest_field('post', 'authorName', array(
        'get_callback'=> function() {return get_the_author();}
    ));
    register_rest_field('note', 'userNoteCount', array(
        'get_callback'=> function() {return count_user_posts(get_current_user_id(),'note');}
    ));
    
}

add_action('rest_api_init', 'testproject_custom_rest');
function pageBanner($args = NULL)
{


    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
      }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
      }


      if (!isset($args['photo'])) {
        if (get_field('page_banner_background') AND !is_archive() AND !is_home()) {
          $args['photo'] = get_field('page_banner_background')['sizes']['pageBanner'];
        } else {
          $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
      }

    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url('<?php echo $args['photo']; ?>');"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
    <?php
}

function testproject_files()
{
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('testproject_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('testproject_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900');

    wp_localize_script('main-university-js','universityData', array(
        'root_url'=> get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')

    ));

}

add_action('wp_enqueue_scripts', 'testproject_files');

function test_project_features()
{
    /*
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    */
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
    add_theme_support('editor-styles');
    add_editor_style(array('https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900','build/style-index.css', 'build/index.css'));

}

add_action('after_setup_theme', 'test_project_features');

function test_project_adjust_queries($query)
{
    if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
        $query->set('posts_per_page', - 1);
    }

    if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'test_project_adjust_queries');

//Redirect Subscriber Accounts out of admin and onto homepage

add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend(){
    $ourCurrentUser = wp_get_current_user();

    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0 ] == 'subscriber'){
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar(){
    $ourCurrentUser = wp_get_current_user();

    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0 ] == 'subscriber'){
        show_admin_bar(false);
    }
}

//Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl(){
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS(){
    wp_enqueue_style('testproject_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('testproject_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900');
}

add_filter('login_headertitle','ourLoginTitle');

function ourLoginTitle(){
    return get_bloginfo('name');
}

//Force note posts to be private

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr){
    
    if($data['post_type'] == 'note'){

        if(count_user_posts(get_current_user_id(),'note')> 4 AND !$postarr['ID']){
            die("You´ve reached your note limit.");
        }

        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);

    }

    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
        $data['post_status'] = "private";
    }
    return $data;
}


class JSXBLock {
    function __construct($name, $renderCallback = null, $data = null){
        $this->name = $name;
        $this->data = $data;
        $this->renderCallback = $renderCallback;
        add_action('init', [$this, 'onInit']);
    }

    function ourRenderCallback($attributes, $content){
        ob_start();
        require get_theme_file_path("/our-blocks/{$this->name}.php");
        return ob_get_clean();
    }
    
    function onInit(){
        wp_register_script($this->name, get_stylesheet_directory_uri() ."/build/{$this->name}.js",array('wp-blocks','wp-editor'));
        
        if($this->data){
            wp_localize_script($this->name, $this->name, $this->data);
        }        

        $ourArgs = array(
            'editor_script' => $this->name
        );

        if($this->renderCallback){
            $ourArgs['render_callback'] = [$this, 'ourRenderCallback'];
        }

        register_block_type("ourblocktheme/{$this->name}", $ourArgs); 
    }
}


class PlaceholderBlock {
    function __construct($name){
        $this->name = $name;
        add_action('init', [$this, 'onInit']);
    }

    function ourRenderCallback($attributes, $content){
        ob_start();
        require get_theme_file_path("/our-blocks/{$this->name}.php");
        return ob_get_clean();
    }
    
    function onInit(){
        wp_register_script($this->name, get_stylesheet_directory_uri() ."/our-blocks/{$this->name}.js",array('wp-blocks','wp-editor'));
                
        register_block_type("ourblocktheme/{$this->name}", array(
            'editor_script' => $this->name,
            'render_callback' => [$this, 'ourRenderCallback']
        )); 
    }
}

// Reg our new modern blocks
function our_new_blocks(){
    register_block_type_from_metadata(__DIR__ . '/build/footer');
    register_block_type_from_metadata(__DIR__ . '/build/header');
    register_block_type_from_metadata(__DIR__ . '/build/eventsandblogs');
    register_block_type_from_metadata(__DIR__ . '/build/singlepost');
    register_block_type_from_metadata(__DIR__ . '/build/page');
    register_block_type_from_metadata(__DIR__ . '/build/blogindex');
    register_block_type_from_metadata(__DIR__ . '/build/programarchive');
    register_block_type_from_metadata(__DIR__ . '/build/singleprogram');
    register_block_type_from_metadata(__DIR__ . '/build/singleprofessor');
    register_block_type_from_metadata(__DIR__ . '/build/mynotes');
    register_block_type_from_metadata(__DIR__ . '/build/eventarchive');
    register_block_type_from_metadata(__DIR__ . '/build/singleevent');
    register_block_type_from_metadata(__DIR__ . '/build/campusarchive');
    register_block_type_from_metadata(__DIR__ . '/build/singlecampus');
}

add_action('init','our_new_blocks');

//new PlaceholderBlock("eventsandblogs");
//new PlaceholderBlock("header");
//new PlaceholderBlock("footer");
//new PlaceholderBlock("singlepost");
//new PlaceholderBlock("page");
//new PlaceholderBlock("blogindex");
//new PlaceholderBlock("programarchive");
//new PlaceholderBlock("singleprogram");
//new PlaceholderBlock("singleprofessor");
//new PlaceholderBlock("mynotes");
//new PlaceholderBlock("eventarchive");
//new PlaceholderBlock("singleevent");
//new PlaceholderBlock("campusarchive");
//new PlaceholderBlock("singlecampus");

new JSXBLock('banner', true, ['fallbackimage' => get_theme_file_uri('/images/library-hero.jpg')]);
new JSXBLock('genericheading', false);
new JSXBLock('genericbutton');
new JSXBLock('slideshow', true);
new JSXBLock('slide', true, ['themeimagepath' => get_theme_file_uri('/images/')]);

function myallowedblocks($allowed_block_types, $editor_context){
    
    if(!empty($editor_context->post)){
        return $allowed_block_types;
    }

    return array('ourblocktheme/header','ourblocktheme/footer');

}

add_filter('allowed_block_types_all','myallowedblocks',10,2);


