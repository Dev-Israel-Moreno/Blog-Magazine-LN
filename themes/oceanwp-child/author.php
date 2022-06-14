<!DOCTYPE html>
<html class="<?php echo esc_attr( oceanwp_html_classes() ); ?>" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php oceanwp_schema_markup( 'html' ); ?>>

	<?php wp_body_open(); ?>

	<?php do_action( 'ocean_before_outer_wrap' ); ?>

	<div id="outer-wrap" class="site clr">

		<a class="skip-link screen-reader-text" href="#main"><?php oceanwp_theme_strings( 'owp-string-header-skip-link', 'oceanwp' ); ?></a>

		<?php do_action( 'ocean_before_wrap' ); ?>

		<div id="wrap" class="clr">
		
		

			<?php do_action( 'ocean_top_bar' ); ?>
			
	             
		

			    <?php echo do_shortcode("[oceanwp_library id='789']"); ?>
				
			
				<div class="aside-social-us">
				
				<?php echo do_shortcode("[oceanwp_library id='7813']"); ?>
				
				</div>
				


			<?php do_action( 'ocean_before_main' ); ?>

			<main id="main" class="site-main clr"<?php oceanwp_schema_markup( 'main' ); ?> role="main">




 <!-- --------------------------------------------------------------------------------------------------------------------- -->

	<?php do_action( 'ocean_before_content_wrap' ); ?>
	
	

	<div id="" class="container clr">

		<?php do_action( 'ocean_before_primary' ); ?>

		<div id="author-container-page" class="author-container-page">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'ocean_before_content_inner' ); ?>

				<?php
				// Check if posts exist.
				if ( have_posts() ) :

					// Elementor `archive` location.
					if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {

						// Add Support For EDD Archive Pages.
						if ( is_post_type_archive( 'download' ) || is_tax( array( 'download_category', 'download_tag' ) ) ) {

							do_action( 'ocean_before_archive_download' );
							?>

							<div class="oceanwp-row <?php echo esc_attr( oceanwp_edd_loop_classes() ); ?>">
								<?php
								// Archive Post Count for clearing float.
								$oceanwp_count = 0;
								while ( have_posts() ) :
									the_post();
									$oceanwp_count++;
									get_template_part( 'partials/edd/archive' );
									if ( oceanwp_edd_entry_columns() === $oceanwp_count ) {
										$oceanwp_count = 0;
									}
								endwhile;
								?>
							</div>

							<?php
							do_action( 'ocean_after_archive_download' );
						} else {
							?>
                         
						 
						 
<!-- --------------------------------DISENNO CUANDO ES UN AUTHOR---------------------------------------- -->

                  <?php

				  				 

                  //if(isGUIColumnist() == true){

                       //echo 'prueba diseño del columnista';

				  //}else{

					//echo 'prueba diseño del autor';

				 // }

                  
				  ?>
				  
				  


                  
<?php

$author = get_the_author();
$description = get_the_author_meta( 'description' );
$url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

/*$roles = $author->roles; */

$profile = get_the_author_meta ( 'profile','ID' );

 echo $profile;

 //if( in_array( "author", (array) $author->roles ) ) {



//	}


 ?>


              
            <div id="" class="">

              
               
					

                   <div class="space"></div>
				   

				   <center><h2 class="title-author">Banco de autores</h2></center>
				   
				   

				   <div id="anwp-col-md-12 blog-entries content-author-area" class="anwp-col-md-12 position-relative <?php oceanwp_blog_wrap_classes(); ?> anwp-row content-author">

				   
				   
                    <div class="anwp-col-md-6 author-data position-relative padding-archive-author">




					   	<?php

						$author = get_the_author();
                        $description = get_the_author_meta( 'description' );
                        $url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

                        /*$roles = $author->roles; */

                        $profile = get_the_author_meta ( 'profile','ID' );

                         echo $profile;

                         if( in_array( "author", (array) $author->roles ) ) {
                         //El usuario actual es columnista


                         }
                         /*
                         if( is_user_logged_in() ) {
                           $user = wp_get_current_user();
                           $author = get_the_author();
                           $role = ( array ) $user->roles;
                          return $role[0];
                         }
                        */
                        $user_id = $args['user_id'];

                        $roles = get_userdata( $user_id );
                        $roles = $roles->roles;

                        echo $roles[0];




						?>

						<div>

                           <a href="<?php echo esc_url( $url ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'oceanwp' ); ?>" rel="author" >
						    <div class="author-avatar-profiles">
                             <?php
                             // Display author avatar.

                              echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'ocean_author_bio_avatar_size', 400 ) ); ?>
							</div>
                           </a>

                     </div><!-- .author-bio-avatar -->


                  <div>


					<h4 class="author-title-profile">

                       <a href="" title="<?php esc_attr_e( 'Visit Author Page', 'oceanwp' ); ?>">

                           <?php echo esc_html( strip_tags( $author ) ); ?>

                       </a>


                      <!--   <p class="column-author"><?php the_author_meta('user_columna'); ?></p> -->


                    </h4><!-- .author-bio-title -->


                    <?php
                     // Outputs the author description if one exists.
                        if ( $description ) : ?>

                          <div class="author-description-profile">

                              <?php echo wp_kses_post( $description ); ?>

                           </div><!-- author-bio-description -->

                    <?php endif; ?>


                    <?php
                         
						if(get_the_author_meta('facebook') ): ?>

                        <center>

                            <div class="profile-social">

			                   <a class="fab fa-facebook-f" target="_blank" rel="noopener noreferrer" href="https://<?php echo get_the_author_meta('facebook'); ?>"></a>

                            </div>


	                <?php endif; ?>


                    <?php
                         if(get_the_author_meta('twitter') ): ?>


                            <div class="profile-social">

			                     <a class="fab fa-twitter" target="_blank" rel="noopener noreferrer" href="https://<?php echo get_the_author_meta('twitter'); ?>"></a>

                            </div>


	                <?php endif; ?>


                    <?php

                         if(get_the_author_meta('instagram') ): ?>


                            <div class="profile-social">

			                     <a class="fab fa-instagram" target="_blank" rel="noopener noreferrer" href="https://<?php echo get_the_author_meta('instagram'); ?>"></a>

                            </div>


	                <?php endif; ?>

                        </center>



                 </div>















					 </div>
					 
					 <div class="anwp-col-md-6 notice position-relative padding-archive-author">

                      <h2 class="heading"> lo más leído </h2>
							<?php
							// Loop through posts.
							
							$args = array(
							          'excerpt_by_words' => 1,
									  'stats_author' => 1,
									   'stats_date' => 1,
									  'thumbnail_width' => 100,
                                     'thumbnail_height' => 55,
									  'range' => 'all',
							        'excerpt_length' => 40,
							        'limit' => 3,
                                   'author' => get_the_author_meta('ID'), 
								   'post_html' => '<li>

<div class="anwp-pg-post-teaser anwp-pg-post-teaser--layout-classic anwp-row anwp-no-gutters container-trending">



<div class="anwp-pg-post-teaser__content anwp-col-md-12 wpp-item-data title-wpp">

<div class="title-trending-author">
{title}
</div>

<div class="meta-trending">

<i class="far fa-user"></i> <span class="author-trending">{author}</span>

| <i class="far fa-eye"> </i>  {views}

| <i class="far fa-comment"></i>  <span class="comment-trending">{comments}</span> 



| <i class="far fa-calendar-alt"> </i>  {date} 


</div>

<p class="wpp-excerpt">{excerpt}</p>

<div class="read-trending-author">
<a href="{url}">Leer más</a>
</div>

</div>

</div>

</li>'
								   
								   
                                 );

                                wpp_get_mostpopular($args);
							
							while ( have_posts() ) :
							
												   
								the_post();
								?>

								<?php
								// Add to counter.
								$oceanwp_count++;
								?>

								<?php
								// Get post entry content.
								//get_template_part( 'partials/entry/layout', get_post_type() );
								?>

								<?php
								// Reset counter to clear floats.
								if ( oceanwp_blog_entry_columns() === $oceanwp_count ) {
									$oceanwp_count = 0;
								}
								?>

							<?php endwhile; ?>
							
							
                        </div>



                 


				</div>	
							
							
						
<!-- ---------------------------------DISENNO CUANDO ES UNA COLUMNA--------------------------------------- -->



 	
	<div id="anwp-col-md-12  blog-entries content-author-area" class="anwp-col-md-12 position-relative <?php oceanwp_blog_wrap_classes(); ?> anwp-row content-author">



    
							<?php
							// Define counter for clearing floats.
							$oceanwp_count = 0;
							?>


    <?php if(get_the_author_meta( 'user_columnaxxx' ) /*the_author_meta('user_columna') */): ?>
                  
				  <div class="anwp-col-md-12 padding-archive-col author-data  ">




					   	<?php

						$author = get_the_author();
                        $description = get_the_author_meta( 'description' );
                        $url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

                        /*$roles = $author->roles; */

                        $profile = get_the_author_meta ( 'profile','ID' );

                         echo $profile;

                         if( in_array( "author", (array) $author->roles ) ) {
                         //El usuario actual es columnista


                         }
                         /*
                         if( is_user_logged_in() ) {
                           $user = wp_get_current_user();
                           $author = get_the_author();
                           $role = ( array ) $user->roles;
                          return $role[0];
                         }
                        */
                        $user_id = $args['user_id'];

                        $roles = get_userdata( $user_id );
                        $roles = $roles->roles;

                        echo $roles[0];




						?>

						


                  <div class="description-columnist">


					<h4 class="author-title-profile-col">

                       <a href="" title="<?php esc_attr_e( 'Visit Author Page', 'oceanwp' ); ?>">

                           <?php echo esc_html( strip_tags( $author ) ); ?>

                       </a>

                      


                        <p class="column-author"><?php the_author_meta('user_columna'); ?></p>


                    </h4><!-- .author-bio-title -->

                    
                    
					<div class="author-social-col">

                      <?php
                         if(get_the_author_meta('facebook') ): ?>

                    

                            <div class="profile-social">

			                   <a class="fab fa-facebook-f" target="_blank" rel="noopener noreferrer" href="https://<?php echo get_the_author_meta('facebook'); ?>"></a>

                            </div>


	                  <?php endif; ?>

                      <?php
                         if(get_the_author_meta('twitter') ): ?>


                            <div class="profile-social">

			                     <a class="fab fa-twitter" target="_blank" rel="noopener noreferrer" href="https://<?php echo get_the_author_meta('twitter'); ?>"></a>

                            </div>


	                  <?php endif; ?>

                      <?php
                         if(get_the_author_meta('instagram') ): ?>


                            <div class="profile-social">

			                     <a class="fab fa-instagram" target="_blank" rel="noopener noreferrer" href="https://<?php echo get_the_author_meta('instagram'); ?>"></a>

                            </div>


	                  <?php endif; ?>

                    
					 
					    </div>

                  </div>
				  
				  
				  <div class="columnist-profile">

                           <a href="<?php echo esc_url( $url ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'oceanwp' ); ?>" rel="author" >
						    <div class="author-avatar-profile">
                             <?php
                             // Display author avatar.

                              echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'ocean_author_bio_avatar_size', 250 ) ); ?>
							</div>
                           </a>

                     </div><!-- .author-bio-avatar -->
			

			</div>
			
			
			
			
			<div class="anwp-col-md-12 notice position-relative padding-archive">

                    
							<?php
							// Loop through posts.
							
							$args = array(
							          'excerpt_by_words' => 1,
									  'stats_author' => 1,
									  'limit' => 3,
									   'stats_date' => 1,
									  'thumbnail_width' => 100,
                                     'thumbnail_height' => 55,
									    'range' => 'all',
							        'excerpt_length' => 45,		        
                                   'author' => get_the_author_meta('ID'), 
								   'post_html' => '<li>

<div class="anwp-pg-post-teaser content-columnist anwp-pg-post-teaser--layout-classic anwp-row anwp-no-gutters container-trending">

<div class="anwp-pg-post-teaser__thumbnail position-relative mb-2 mb-md-0 anwp-col-md-6 img-trending">
{thumb_img} 
</div>

<div class="anwp-pg-post-teaser__content anwp-col-md-6 wpp-item-data">

<div class="title-trending">
{title}
</div>

<div class="meta-trending">

<i class="far fa-user"></i> <span class="author-trending">{author}</span>


| <i class="far fa-eye"> </i>  {views}

| <i class="far fa-comment"></i>  <span class="comment-trending">{comments}</span> 



| <i class="far fa-calendar-alt"> </i>  {date} 


</div>

<p class="wpp-excerpt">{excerpt}</p>

<div class="read-trending">
<a href="{url}">Leer más</a>
</div>

</div>

</div>

</li>
<br>'
								   
								   
                                 );

                                wpp_get_mostpopular($args);
							
							while ( have_posts() ) :
							
												   
								the_post();
								?>

								<?php
								// Add to counter.
								$oceanwp_count++;
								?>

								<?php
								// Get post entry content.
								//get_template_part( 'partials/entry/layout', get_post_type() );
								?>

								<?php
								// Reset counter to clear floats.
								if ( oceanwp_blog_entry_columns() === $oceanwp_count ) {
									$oceanwp_count = 0;
								}
								?>
								
								

							<?php endwhile; ?>
							
							
                        </div>
			
		
		
			
			
		 <?php endif; ?>
		 
		 
           




<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                 


                     
						</div><!-- #blog-entries -->

							<?php
							// Display post pagination.
							//oceanwp_blog_pagination();
						}
					}
					?>

					<?php
					// No posts found.
					else :
						?>

						<?php
						// Display no post found notice.
						get_template_part( 'partials/none' );
						?>

					<?php endif; ?>
					

				<?php do_action( 'ocean_after_content_inner' ); ?>
				
				

			</div><!-- #content -->

			<?php do_action( 'ocean_after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'ocean_after_primary' ); ?>
		
		

	</div><!-- #content-wrap -->

	<?php do_action( 'ocean_after_content_wrap' ); ?>
	
	
	
	
			    <?php echo do_shortcode("[oceanwp_library id='801']"); ?>
				

<div class="author-footer-archive"> <?php get_footer(); ?>  </div>
