<?php
/*
Plugin Name: Cool Post Slider
Description: Display slideshow of posts.
Tags: post slider, posts, category, type, tag, slider
Author URI: http://kostumegiganten.dk/
Author: Kjeld Hansen
Text Domain: ripsl_post_slider
Requires at least: 4.0
Tested up to: 4.4.2
Version: 1.0
*/


 if ( ! defined( 'ABSPATH' ) ) exit; 
add_action('admin_menu','ripsl_post_slider_admin_menu');
function ripsl_post_slider_admin_menu() { 
    add_menu_page(
		"Post Slider",
		"Slider",
		8,
		__FILE__,
		"ripsl_post_slider_admin_menu_list",
		plugins_url( 'img/plugin-icon.png', __FILE__) 
	); 
}

function ripsl_post_slider_admin_menu_list(){
	include 'post-listing-admin.php';
}


//add_action( 'admin_head', 'ripsl_post_slider_admin_css' );
add_action( 'admin_enqueue_scripts', 'ripsl_post_slider_admin_css' );
function ripsl_post_slider_admin_css(){
	wp_register_style( 'ripsl_post_slider_admin_wp_admin_css', plugins_url( '/css/admin.css', __FILE__), false, '1.0.0' );
    wp_enqueue_style( 'ripsl_post_slider_admin_wp_admin_css' );	
}

if (!shortcode_exists('postSlider')) {
	add_shortcode('postSlider', 'ripsl_post_slider_ri_list_posts');
}

//[postList type='post' cat='23' tag='24' ordby='date' ord='asc' count='10' offset='0' temp='t1' hide='date,author' exrpt='50']

if (!function_exists('ripsl_post_slider_ri_list_posts')){
function ripsl_post_slider_ri_list_posts($args){
	$licol = 3; if($class=='dgg'){ $licol = 12; $class .= ' rinp'; } 
	$riid = '';
	if($args[temp]=='t1'){
		$riid = 'ripl_template1';
		$ricss = plugins_url( '/css/t1.css', __FILE__);
	}
	else if($args[temp]=='t2'){
		$riid = 'ripl_template2';
		$ricss = plugins_url( '/css/t2.css', __FILE__);
	}
	$rijs = plugins_url( '/js/post-slide.js', __FILE__);
	$ricssslide = plugins_url( '/css/slide.css', __FILE__);
	
	wp_enqueue_style( 'style-ripsl-post-slider', $ricss );
	wp_enqueue_style( 'style-ripsl-post-slider-css', $ricssslide );
	wp_enqueue_script( 'ps-script-ripsl-post-slider', $rijs );
	?>
    
	   	<ul class="postsri act-post-slide" id="<?php echo $riid; ?>">
            	<?php
					$custom_args = array(
					  'post_type' => $args[type],
					  'posts_per_page' => $args[count],
					  'tag' => $args[tag],
					  'category_name' => $args[cat],
					  'offset' => $args[offset],
					  'orderby' => $args[ordby],
					  'order'   => $args[ord],
					);
					$custom_query = new WP_Query( $custom_args );
			    ?>
              <?php if ( $custom_query->have_posts() ) : ?>
              <?php $ric=0; while ( $custom_query->have_posts() ) : $custom_query->the_post(); 
			  	$ric++;
				$rimeta = get_post_meta(get_the_id());
			  	 ?>
		   
            <?php
			if($args[temp]=='t1'){  ?>
				 <!-- Template 1 -->
                <li> 
                    <span><a class="" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) {    the_post_thumbnail(array(150,150));	} ?></a></span>
                    <h2><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <label>Posted by : <?php echo get_the_author(); ?> | Posted on : <?php echo get_the_date(); ?></label>
                    <div class="riexcerpt">
                      <p><?php ripsl_post_slider_excerpt($args[exrpt]); ?></p>
                    </div>
                </li>
			<?php }
			else if($args[temp]=='t2'){  ?>
				<!-- Template 2 -->
                <li> 
                    <span><a class="" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) {    the_post_thumbnail(array(150,150));	} ?></a></span>
                    <div class="postdesri">
                        <h2><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                       <label>Posted by : <?php echo get_the_author(); ?> | Posted on : <?php echo get_the_date(); ?></label>
                        <div class="riexcerpt">
                            <p><?php ripsl_post_slider_excerpt($args[exrpt]); ?></p>
                        </div>
                    </div>
                </li>
			<?php } ?>
           
		   <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
			<?php else:  ?>
            <li><p><?php _e( 'Sorry, no posts available.' ); ?></p></li>
            <?php endif; ?>
            </ul>
       
        <?php
}
}



function ripsl_post_slider_excerpt($charlength=50) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}


