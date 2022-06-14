<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package OceanWP WordPress theme
 */

get_header(); ?>

<?php echo do_shortcode("[oceanwp_library id='527']"); ?> 

              <div class="aside-social-columnist">
				
				<?php echo do_shortcode("[oceanwp_library id='7813']"); ?>
				
				</div>

	<?php do_action( 'ocean_before_content_wrap' ); ?>

	<div id="content-wrap" class="container-columnist-category clr">

		<?php do_action( 'ocean_before_primary' ); ?>

		<div id="primary" class="content-area clr">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'ocean_before_content_inner' ); ?>
				
				
				
				
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
				
				
				
				
				

				<?php
				// Check if posts exist.
				if ( have_posts() ) :

					// Elementor `archive` location.
				

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
						}


						else {
							?>
						<div id="blog-entries" class="<?php oceanwp_blog_wrap_classes(); ?>">

							<?php
							// Define counter for clearing floats.
							$oceanwp_count = 0;
							?>

							<?php
							// Loop through posts.
							while ( have_posts() ) :
								the_post();
								
								?>

								<?php
								// Add to counter.
								$oceanwp_count++;
								?>

								<?php
								// Get post entry content.
								get_template_part( 'partials/entry/layout', get_post_type() );
								?>

								<?php
								// Reset counter to clear floats.
								if ( oceanwp_blog_entry_columns() === $oceanwp_count ) {
									$oceanwp_count = 0;
								}
								?>

							<?php endwhile; ?>

						</div><!-- #blog-entries -->

							<?php
							// Display post pagination.
							oceanwp_blog_pagination();
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
	
	<?php echo do_shortcode("[oceanwp_library id='632']"); ?> 

<?php get_footer(); ?>
