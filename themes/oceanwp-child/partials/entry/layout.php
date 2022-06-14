<?php
/**
 * Default post entry layout
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post format.
$format = get_post_format();

// Blog style.
$style = get_theme_mod( 'ocean_blog_style', 'large-entry' );

// Quote format is completely different.
if ( 'quote' === $format ) {

	// Get quote entry content.
	get_template_part( 'partials/entry/quote' );

	return;

}

// If thumbnail style.
if ( 'thumbnail-entry' === $style ) {
	get_template_part( 'partials/entry/thumbnail-style/layout' );
} else {

	// Add classes to the blog entry post class.
	$classes = oceanwp_post_entry_classes(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

   <!-- blog-entry-inner clr anwp-pg-post-teaser anwp-pg-post-teaser--layout-classic anwp-row anwp-no-gutters container-trending -->
		<div class="anwp-pg-post-teaser anwp-pg-post-teaser--layout-classic anwp-row anwp-no-gutters container-trending">
		

			<?php
			// Get elements.
			$elements = oceanwp_blog_entry_elements_positioning();
			?>
			
			
		    

            <?php
			// Loop through elements.
			foreach ( $elements as $element ) {
          	
			
				// Featured Image.
				if ( 'featured_image' === $element ) {
			 ?>

			  <div class="anwp-pg-post-teaser__thumbnail position-relative mb-2 mb-md-0 anwp-col-md-6 img-trending">
			<!--Inicia BLK Imagen --> 
           
			 <?php  
					get_template_part( 'partials/entry/media/blog-entry', $format );
              ?>
			  
			   
			 <!--Finaliza BLK Imagen -->		 
			 </div>
			 
			 <?php  
			 
				}
			 }	
			  ?>
			 
			
			 

		 
			  <div class="anwp-pg-post-teaser__content anwp-col-md-6 wpp-item-data">
			  
			 <?php
			 foreach ( $elements as $element ) {
				 
				
				 
				// Title.
				if ( 'title' === $element ) {
             
					get_template_part( 'partials/entry/header' );
                
				}
              ?>
			  <?php
				// Meta.
				if ( 'meta' === $element ) {

					get_template_part( 'partials/entry/meta' );
                      
				}
               ?>
			   <?php
				// Content.
				if ( 'content' === $element ) {

					get_template_part( 'partials/entry/content' );
                 
				}
                ?>
				<?php
				// Read more button.
				if ( 'read_more' === $element ) {

					get_template_part( 'partials/entry/readmore' );
                    
				}
				 ?>
			
			

			
             <?php			
			 	
			 
			 
			}

			?>
				
				 </div>

	

		</div><!-- .blog-entry-inner -->

	</article><!-- #post-## -->

	<?php
}
?>
