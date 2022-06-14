<?php

if ( ! function_exists( 'authors_list_sc' ) ) :

    /**
     * Shortcode
     *
     * @since 1.0.0
     */
    function authors_list_sc( $atts = false, $content = false ) {        

        // if no atts supplied make it an empty array
        if ( ! $atts ) $atts = array();	

        // default values
        $defaults = array(
            'style' => '1',
            'columns' => '4',
            'columns_direction' => 'horizontal',
            'avatar_size' => 500,
            'avatar_meta_key' => '',
            'amount' => false,
            'show_avatar' => 'yes',
            'show_title' => 'yes',
            'show_count' => 'yes',
            'show_bio' => 'yes',
            'show_link' => 'yes',
            'orderby' => 'post_count',
            'order' => 'DESC',
            'skip_empty' => 'yes',
            'minimum_posts_count' => 0,
            'bio_word_trim' => false,
            'only_authors' => 'yes',
            'exclude' => '',
            'include' => '',
            'roles' => '',
            'latest_post_after' => '',
            'post_types' => '',
            'name_starts_with' => '',
            'link_to' => 'archive',
            'link_to_meta_key' => '',
            'pagination' => 'no',
            'count_text' => esc_html__( 'posts', 'authors-list' ),
            
            'categories' => '',
            'taxonomy' => '',
            'terms' => '',

            'before_avatar' => '',
            'before_title' => '',
            'before_count' => '',
            'before_bio' => '',
            'before_link' => '',

            'after_avatar' => '',
            'after_title' => '',
            'after_count' => '',
            'after_bio' => '',
            'after_link' => '',

            'title_element' => 'div',

            'bp_member_types' => '',
        );

        // merge settings
        $settings = array_merge( $defaults, $atts );    

        // atts
        $atts = array(
            'fields'  => 'ID',
            'orderby' => $settings['orderby'],
            'order'   => $settings['order'],
        );

        // pagination
        if ( $settings['pagination'] == 'yes' && $settings['amount'] ) {
            $total_users = count_users();
            $total_users = $total_users['avail_roles']['author'];
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $total_pages = ceil( $total_users / $settings['amount'] );
            $atts['number'] = $settings['amount'];
            $atts['offset'] = $paged ? ($paged - 1) * $settings['amount'] : 0;
        }

        // get last post date
        $get_last_post_date = false;
        if ( $settings['orderby'] == 'post_date' || ! empty( $settings['latest_post_after'] ) ) {
            $get_last_post_date = true;
        }

        // order by last name
        if ( $settings['orderby'] == 'last_name' ) {
            $atts['meta_key'] = 'last_name';
            $atts['orderby'] = 'meta_value';
        }

        // order by first name
        if ( $settings['orderby'] == 'first_name' ) {
            $atts['meta_key'] = 'first_name';
            $atts['orderby'] = 'meta_value';
        }
        
        // only authors
        if ( $settings['only_authors'] == 'yes' ) {
            $atts['who'] = 'authors';
        }

        // exclude
        if ( ! empty( $settings['exclude'] ) ) {
            $atts['exclude'] = explode( ',', $settings['exclude'] );
        }

        // include
        if ( ! empty( $settings['include'] ) ) {
            $atts['include'] = explode( ',', $settings['include'] );
        }

        // switch "categories" to "taxonomy" and "terms"
        if ( ! empty( $settings['categories'] ) ) {
            $settings['taxonomy'] = 'category';
            $settings['terms'] = $settings['categories'];
        }

        // default taxonomy
        if ( empty( $settings['taxonomy'] ) ) {
            $settings['taxonomy'] = 'category';
        }

        // include based on taxonomy/term
        if ( ! empty( $settings['terms'] ) ) {
            
            // array of supplied categories
            $terms = explode( ',', $settings['terms'] );

            // query arguments
            $args = array(
                'posts_per_page'         => -1,
                'orderby'                => 'author',
                'order'                  => 'ASC',
                'cache_results'          => false,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'tax_query'              => array(
                    array(
                        'taxonomy'         => $settings['taxonomy'],
                        'terms'            => $terms,
                        'include_children' => true
                    )
                )
            );

            // get posts
            $posts_array = get_posts( $args );
            
            // get author IDs
            $post_author_ids = false;
            if ( $posts_array ) {
                $post_author_ids = wp_list_pluck( $posts_array, 'post_author' );
                $post_author_ids = array_unique( $post_author_ids );
            }

            if ( is_array( $post_author_ids ) ) {
                if ( empty( $atts['include'] ) ) {
                    $atts['include'] = $post_author_ids;
                } else {
                    $atts['include'] = array_merge( $atts['include'], $post_author_ids );
                }
            }            

        }

        // roles
        if ( ! empty( $settings['roles'] ) ) {
            $atts['role__in'] = explode( ',', $settings['roles'] );
            unset( $atts['who'] );
        }

        // post types
        if ( ! empty( $settings['post_types'] ) ) {
            $atts['has_published_posts'] = explode( ',', $settings['post_types'] );
            unset( $atts['who'] );
            $settings['skip_empty'] = 'no';
        }

        // limit by first letter
        if ( ! empty( $settings['name_starts_with'] ) ) {
            $atts['search'] = sanitize_text_field( $settings['name_starts_with'] ) . '*';
            $atts['search_columns'] = array(
                'display_name',
            );
        }

        // get authors order by post count
        $authors_ids = get_users( $atts );        

        // start output buffer
        ob_start();

        $item_class = '';
        switch ( $settings['columns'] ) {
            case '4':
                $item_class .= 'authors-list-col-3';
                break;
            case '3':
                $item_class .= 'authors-list-col-4';
                break;
            case '2':
                $item_class .= 'authors-list-col-6';
                break;
        }

        $output_items = array();
        $count = 0;
        $real_count = 0;

        ?><style>.authors-list-cols-dir-horizontal .authors-list-col{display:block;float:left;margin-right:3.42%}.authors-list-cols-dir-horizontal .authors-list-col-1{width:5.198%}.authors-list-cols-dir-horizontal .authors-list-col-2{width:13.81%}.authors-list-cols-dir-horizontal .authors-list-col-3{width:22.43%}.authors-list-cols-dir-horizontal .authors-list-col-4{width:31.05%}.authors-list-cols-dir-horizontal .authors-list-col-5{width:39.67%}.authors-list-cols-dir-horizontal .authors-list-col-6{width:48.29%}.authors-list-cols-dir-horizontal .authors-list-col-7{width:56.9%}.authors-list-cols-dir-horizontal .authors-list-col-8{width:65.52%}.authors-list-cols-dir-horizontal .authors-list-col-9{width:74.14%}.authors-list-cols-dir-horizontal .authors-list-col-10{width:82.76%}.authors-list-cols-dir-horizontal .authors-list-col-11{width:91.38%}.authors-list-cols-dir-horizontal .authors-list-col-12{width:100%}.authors-list-cols-dir-horizontal .authors-list-col-last{margin-right:0}.authors-list-cols-dir-horizontal .authors-list-col-first{clear:both}.authors-list-cols-dir-horizontal.authors-list-cols-2 .authors-list-col:nth-child(2n){margin-right:0}.authors-list-cols-dir-horizontal.authors-list-cols-2 .authors-list-col:nth-child(2n+1){clear:both}.authors-list-cols-dir-horizontal.authors-list-cols-3 .authors-list-col:nth-child(3n){margin-right:0}.authors-list-cols-dir-horizontal.authors-list-cols-3 .authors-list-col:nth-child(3n+1){clear:both}.authors-list-cols-dir-horizontal.authors-list-cols-4 .authors-list-col:nth-child(4n){margin-right:0}.authors-list-cols-dir-horizontal.authors-list-cols-4 .authors-list-col:nth-child(4n+1){clear:both}.authors-list-cols-dir-vertical{column-gap:3.42%}.authors-list-cols-dir-vertical.authors-list-cols-2{column-count:2}.authors-list-cols-dir-vertical.authors-list-cols-3{column-count:3}.authors-list-cols-dir-vertical.authors-list-cols-3{column-count:3}.authors-list-cols-dir-vertical.authors-list-cols-4{column-count:4}.authors-list-clearfix:after,.authors-list-clearfix:before{content:" ";display:table}.authors-list-clearfix:after{clear:both}.authors-list-item{margin-bottom:30px;position:relative}.authors-list-cols-dir-vertical .authors-list-item{break-inside:avoid-column;page-break-inside:avoid}.authors-list-item-thumbnail{margin-bottom:20px;position:relative}.authors-list-item-thumbnail a,.authors-list-item-thumbnail img{display:block;position:relative;border:0}.authors-list-item-thumbnail img{max-width:100%;height:auto}.authors-list-item-title{font-size:22px;font-weight:700;margin-bottom:5px}.authors-list-item-title a{color:inherit}.authors-list-item-subtitle{margin-bottom:5px;font-size:80%}.authors-list-item-social{margin-bottom:10px}.authors-list-item-social a{font-size:15px;margin-right:5px;text-decoration:none}.authors-list-item-social svg{width:15px}.authors-list-item-social-facebook{fill:#3b5998}.authors-list-item-social-instagram{fill:#405de6}.authors-list-item-social-linkedin{fill:#0077b5}.authors-list-item-social-pinterest{fill:#bd081c}.authors-list-item-social-tumblr{fill:#35465c}.authors-list-item-social-twitter{fill:#1da1f2}.authors-list-item-social-youtube{fill:red}.authors-list-item-excerpt{margin-bottom:10px}.authors-list-items-s2 .authors-list-item-main{position:absolute;bottom:0;left:0;right:0;padding:30px;color:#fff;background:rgba(0,0,0,.3)}.authors-list-items-s2 .authors-list-item-thumbnail{margin-bottom:0}.authors-list-items-s2 .authors-list-item-title{color:inherit}.authors-list-items-s2 .authors-list-item-link{color:inherit}.authors-list-items-s3 .authors-list-item-thumbnail{margin-bottom:0}.authors-list-items-s3 .authors-list-item-main{position:absolute;bottom:0;left:0;right:0;top:0;padding:30px;opacity:0;transform:scale(0);transition:all .3s;background:#fff;border:2px solid #eee}.authors-list-items-s3 .authors-list-item:hover .authors-list-item-main{opacity:1;transform:scale(1)}.authors-list-items-s4 .authors-list-item-thumbnail{float:left;margin-right:20px;width:25%}.authors-list-item-s4 .authors-list-item-main{overflow:hidden}.author-list-item-after-avatar,.author-list-item-after-bio,.author-list-item-after-count,.author-list-item-after-link,.author-list-item-after-title,.author-list-item-before-avatar,.author-list-item-before-bio,.author-list-item-before-count,.author-list-item-before-link,.author-list-item-before-title{margin-bottom:5px}@media only screen and (max-width:767px){.authors-list-cols-dir-horizontal .authors-list-col{width:100%;margin-right:0!important}.authors-list-cols-dir-vertical{column-count:1!important}}.authors-list-pagination{text-align:center}.authors-list-pagination li{display:inline-block;margin:0 2px}.authors-list-pagination li a,.authors-list-pagination li>span{display:inline-block;border:1px solid rgba(0,0,0,.2);padding:10px;line-height:1}</style><?php
        ?><div class="authors-list-items authors-list-items-s<?php echo $settings['style']; ?> authors-list-clearfix authors-list-cols-<?php echo esc_attr( $settings['columns'] ); ?> authors-list-cols-dir-<?php echo esc_attr( $settings['columns_direction'] ); ?>"><?php

            // loop through each author
            foreach ( $authors_ids as $author_id ) : $count++;

                // get post count
                $post_types = 'post';
                if ( ! empty( $settings['post_types'] ) ) {
                    $post_types = explode( ',', $settings['post_types'] );
                }
                $post_count = count_user_posts( $author_id, $post_types, true );

                // no posts, end
                if ( ! $post_count && $settings['skip_empty'] == 'yes' ) {
                    continue;
                }

                // less than minimum posts, end
                if ( $post_count < $settings['minimum_posts_count'] ) {
                    continue;
                }

                // buddypress member type
                if ( ! empty( $settings['bp_member_types'] ) && function_exists( 'bp_get_member_type' ) ) {
                    $bp_member_types = explode( ',', $settings['bp_member_types'] );
                    if ( ! in_array( bp_get_member_type( $author_id ), $bp_member_types ) ) {
                        continue;
                    }
                }

                // get last post date if needed
                if ( $get_last_post_date ) {

                    // skip if no posts
                    if ( ! $post_count ) {
                        $latest_post_date_unix = 1;
                    }

                    // get latest post
                    $latest_post = get_posts(array(
                        'author'      => $author_id,
                        'orderby'     => 'date',
                        'order'       => 'desc',
                        'numberposts' => 1
                    ));

                    $latest_post_date_unix = strtotime( $latest_post[0]->post_date );
                    $latest_post_date_ymd = date( 'yyyymmdd', strtotime( $latest_post[0]->post_date ) );

                } else {

                    $latest_post_date_unix = 1;
                    $latest_post_date_ymd = 1;

                }
                
                // skip if latest post older than date limit
                if ( ! empty( $settings['latest_post_after'] ) ) {

                    // skip if no posts
                    if ( ! $post_count ) {
                        continue;
                    }

                    if ( $settings['latest_post_after'] == 'daily' ) {
                        $latest_post_date = $latest_post_date_ymd;
                        $limit_post_date = current_time( 'yyyymmdd' );
                    } else {
                        $latest_post_date = $latest_post_date_unix;
                        $limit_post_date = strtotime( $settings['latest_post_after'] . ' days ago' );
                    }

                    if ( $latest_post_date < $limit_post_date ) {
                        continue;
                    }

                }

                // for ordering by comment count
                $comment_count = 0;
                if ( $settings['orderby'] == 'comment_count' ) {
                    $comment_count = get_comments( array(
                        'post_author' => $author_id,
                        'fields' => 'ids',
                        'count' => true,
                        'update_comment_meta_cache' => false,
                        'update_comment_post_cache' => false,
                    ));
                }

                // vars
                $name = get_the_author_meta( 'display_name', $author_id );
                $bio = get_the_author_meta( 'description', $author_id );
                $custom_avatar = authors_list_get_meta( $author_id, $settings['avatar_meta_key'] );
                $posts_url = get_author_posts_url( $author_id );
            
                if ( $settings['link_to'] == 'bbpress_profile' && function_exists( 'bbp_get_user_profile_link' ) ) {
                    $posts_url = bbp_get_user_profile_url( $author_id );
                }

                if ( $settings['link_to'] == 'buddypress_profile' && function_exists( 'bp_core_get_user_domain' ) ) {
                    $posts_url = bp_core_get_user_domain( $author_id );
                }

                if ( $settings['link_to'] == 'meta' && ! empty( $settings['link_to_meta_key'] ) ) {
                    $posts_url = authors_list_get_meta( $author_id, sanitize_text_field( $settings['link_to_meta_key'] ) );
                }

                // start output buffer
                ob_start();
                ?>

                <div class="anwp-row anwp-no-gutters authors-list-item authors-list-item-clearfix authors-list-col <?php echo esc_attr( $item_class ); ?>">
				
			     <!-- ---------------------------------------------------------------------------------------------------- -->
				 
				 <!-- SI EL USUARIO TIENE ROL STAFF -->
				 <?php if ( $settings['roles'] == 'staff' ) : ?>
				 
				 <?php if ( $settings['show_avatar'] == 'yes' ) : ?>
                        <div class="authors-list-item-thumbnail position-relative mb-2 mb-md-0 anwp-col-md-6">
                            <?php if ( $settings['before_avatar'] ) : ?>
                                <div class="author-list-item-before-avatar"><?php echo authors_list_parse_vars( $author_id, $settings['before_avatar'] ); ?></div>
                            <?php endif; ?>
                            <a href="<?php echo $posts_url; ?>">
                                <?php if ( ! empty( $custom_avatar ) ) : ?>
                                    <img src="<?php echo esc_url( $custom_avatar ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                                <?php else : ?>
                                    <?php echo get_avatar( $author_id, $settings['avatar_size'], '', esc_attr( $name ) ); ?>
                                <?php endif; ?>
                            </a>
                            <?php if ( $settings['after_avatar'] ) : ?>
                                <div class="author-list-item-after-avatar"><?php echo authors_list_parse_vars( $author_id, $settings['after_avatar'] ); ?></div>
                            <?php endif; ?>
                        </div><!-- .team-item-thumbnail -->
                    <?php endif; ?>
				 
				
				 
				  <div class="authors-list-item-main anwp-col-md-6 wpp-item-data">

                        <?php if ( $settings['before_title'] ) : ?>
                            <div class="author-list-item-before-title"><?php echo authors_list_parse_vars( $author_id, $settings['before_title'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_title'] == 'yes' ) : ?>
                            <<?php echo esc_attr( $settings['title_element'] ); ?> class="authors-list-item-title"><a href="<?php echo $posts_url; ?>"><?php echo esc_html( $name ); ?></a></<?php echo esc_attr( $settings['title_element'] ); ?>>
                        <?php endif; ?>
                        
                        <?php if ( $settings['after_title'] ) : ?>
                            <div class="author-list-item-after-title"><?php echo authors_list_parse_vars( $author_id, $settings['after_title'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['before_count'] ) : ?>
                            <div class="author-list-item-before-count"><?php echo authors_list_parse_vars( $author_id, $settings['before_count'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_count'] == 'yes' ) : ?>
                            <?php                                
                                $count_text = $settings['count_text'];
                                if ( strpos( $count_text, ',' ) !== false ) {
                                    $count_text = explode( ',', $count_text );
                                }
                                if ( is_array( $count_text ) && count( $count_text ) == 3 ) {
                                    if ( $post_count == 0 ) {
                                        $count_text = $count_text[0];
                                    } elseif ( $post_count == 1 ) {
                                        $count_text = $count_text[1];
                                    } else {
                                        $count_text = $count_text[2];
                                    }
                                    $count_text = str_replace( '%', $post_count, $count_text );
                                } else {
                                    $count_text = $post_count . ' ' . $count_text;
                                }
                            ?>
                            <div class="authors-list-item-subtitle"><?php echo $count_text; ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['after_count'] ) : ?>
                            <div class="author-list-item-after-count"><?php echo authors_list_parse_vars( $author_id, $settings['after_count'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['before_bio'] ) : ?>
                            <div class="author-list-item-before-bio"><?php echo authors_list_parse_vars( $author_id, $settings['before_bio'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_bio'] == 'yes' ) : ?>
                            <div class="authors-list-item-excerpt"><?php 
                                if ( $settings['bio_word_trim'] ) {
                                    echo wp_trim_words( $bio, intval( $settings['bio_word_trim'] ) ); 
                                } else {
                                    echo $bio;
                                }
                            ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['after_bio'] ) : ?>
                            <div class="author-list-item-after-bio"><?php echo authors_list_parse_vars( $author_id, $settings['after_bio'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['before_link'] ) : ?>
                            <div class="author-list-item-before-link"><?php echo authors_list_parse_vars( $author_id, $settings['before_link'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_link'] == 'yes' ) : ?>
                            <a href="<?php echo $posts_url; ?>" class="authors-list-item-link" aria-label="<?php esc_attr_e( $name ) ?> - <?php esc_attr_e( 'View Posts &rarr;', 'authors-list' ); ?>"><?php esc_html_e( 'View Posts &rarr;', 'authors-list' ); ?></a>
                        <?php endif; ?>

                        <?php if ( $settings['after_link'] ) : ?>
                            <div class="author-list-item-after-link"><?php echo authors_list_parse_vars( $author_id, $settings['after_link'] ); ?></div>
                        <?php endif; ?>

                    </div><!-- .team-item-main -->
					
					 <p><hr class="line-us"></hr></p>
					  <span><hr class="line-us"></hr></span>
					   <div><hr class="line-us"></hr></div>
					   <center><hr class="line-us"></hr></center>
					   
				  <?php endif; ?>
				
				    
				
				 <!-- ---------------------------------------------------------------------------------------------------- -->
				 
				 <?php if ( $settings['roles'] == 'author' || $settings['roles'] == 'columnista'  ) : ?>

                    <?php if ( $settings['show_avatar'] == 'yes' ) : ?>
                        <div class="authors-list-item-thumbnail">
                            <?php if ( $settings['before_avatar'] ) : ?>
                                <div class="author-list-item-before-avatar"><?php echo authors_list_parse_vars( $author_id, $settings['before_avatar'] ); ?></div>
                            <?php endif; ?>
                            <a href="<?php echo $posts_url; ?>">
                                <?php if ( ! empty( $custom_avatar ) ) : ?>
                                    <img src="<?php echo esc_url( $custom_avatar ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                                <?php else : ?>
                                    <?php echo get_avatar( $author_id, $settings['avatar_size'], '', esc_attr( $name ) ); ?>
                                <?php endif; ?>
                            </a>
                            <?php if ( $settings['after_avatar'] ) : ?>
                                <div class="author-list-item-after-avatar"><?php echo authors_list_parse_vars( $author_id, $settings['after_avatar'] ); ?></div>
                            <?php endif; ?>
                        </div><!-- .team-item-thumbnail -->
                    <?php endif; ?>

                    <div class="authors-list-item-main">

                        <?php if ( $settings['before_title'] ) : ?>
                            <div class="author-list-item-before-title"><?php echo authors_list_parse_vars( $author_id, $settings['before_title'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_title'] == 'yes' ) : ?>
                            <<?php echo esc_attr( $settings['title_element'] ); ?> class="authors-list-item-title"><a href="<?php echo $posts_url; ?>"><?php echo esc_html( $name ); ?></a></<?php echo esc_attr( $settings['title_element'] ); ?>>
                        <?php endif; ?>
                        
                        <?php if ( $settings['after_title'] ) : ?>
                            <div class="author-list-item-after-title"><?php echo authors_list_parse_vars( $author_id, $settings['after_title'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['before_count'] ) : ?>
                            <div class="author-list-item-before-count"><?php echo authors_list_parse_vars( $author_id, $settings['before_count'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_count'] == 'yes' ) : ?>
                            <?php                                
                                $count_text = $settings['count_text'];
                                if ( strpos( $count_text, ',' ) !== false ) {
                                    $count_text = explode( ',', $count_text );
                                }
                                if ( is_array( $count_text ) && count( $count_text ) == 3 ) {
                                    if ( $post_count == 0 ) {
                                        $count_text = $count_text[0];
                                    } elseif ( $post_count == 1 ) {
                                        $count_text = $count_text[1];
                                    } else {
                                        $count_text = $count_text[2];
                                    }
                                    $count_text = str_replace( '%', $post_count, $count_text );
                                } else {
                                    $count_text = $post_count . ' ' . $count_text;
                                }
                            ?>
                            <div class="authors-list-item-subtitle"><?php echo $count_text; ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['after_count'] ) : ?>
                            <div class="author-list-item-after-count"><?php echo authors_list_parse_vars( $author_id, $settings['after_count'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['before_bio'] ) : ?>
                            <div class="author-list-item-before-bio"><?php echo authors_list_parse_vars( $author_id, $settings['before_bio'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_bio'] == 'yes' ) : ?>
                            <div class="authors-list-item-excerpt"><?php 
                                if ( $settings['bio_word_trim'] ) {
                                    echo wp_trim_words( $bio, intval( $settings['bio_word_trim'] ) ); 
                                } else {
                                    echo $bio;
                                }
                            ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['after_bio'] ) : ?>
                            <div class="author-list-item-after-bio"><?php echo authors_list_parse_vars( $author_id, $settings['after_bio'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['before_link'] ) : ?>
                            <div class="author-list-item-before-link"><?php echo authors_list_parse_vars( $author_id, $settings['before_link'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( $settings['show_link'] == 'yes' ) : ?>
                            <a href="<?php echo $posts_url; ?>" class="authors-list-item-link" aria-label="<?php esc_attr_e( $name ) ?> - <?php esc_attr_e( 'View Posts &rarr;', 'authors-list' ); ?>"><?php esc_html_e( 'View Posts &rarr;', 'authors-list' ); ?></a>
                        <?php endif; ?>

                        <?php if ( $settings['after_link'] ) : ?>
                            <div class="author-list-item-after-link"><?php echo authors_list_parse_vars( $author_id, $settings['after_link'] ); ?></div>
                        <?php endif; ?>

                    </div><!-- .team-item-main -->
					
					<?php endif; ?>

                </div><!-- .authors-list-item -->

                <?php

                $output_item = ob_get_contents();
                ob_end_clean();

                // end output buffer
                $output_items[] = array(
                    'date_unix' => $latest_post_date_unix,
                    'comment_count' => $comment_count,
                    'post_count' => $post_count,
                    'output'    => $output_item,
                );

                $real_count++;

                // limit reached, end
                if ( $settings['amount'] && $real_count >= $settings['amount'] ) {
                    break;
                }

            endforeach;

            // order by latest post date
            if ( $settings['orderby'] == 'post_date' ) {
                $array_column = array_column( $output_items, 'date_unix' );
                if ( $settings['order'] == 'DESC' ) {
                    array_multisort( $array_column, SORT_DESC, SORT_NUMERIC, $output_items );
                } else {
                    array_multisort( $array_column, SORT_ASC, SORT_NUMERIC, $output_items );
                }
            }

            // order by comment count
            if ( $settings['orderby'] == 'comment_count' ) {
                $array_column = array_column( $output_items, 'comment_count' );
                if ( $settings['order'] == 'DESC' ) {
                    array_multisort( $array_column, SORT_DESC, SORT_NUMERIC, $output_items );
                } else {
                    array_multisort( $array_column, SORT_ASC, SORT_NUMERIC, $output_items );
                }
            }

            // order by all post (CPT) count
            if ( $settings['orderby'] == 'all_post_count' ) {
                $array_column = array_column( $output_items, 'post_count' );
                if ( $settings['order'] == 'DESC' ) {
                    array_multisort( $array_column, SORT_DESC, SORT_NUMERIC, $output_items );
                } else {
                    array_multisort( $array_column, SORT_ASC, SORT_NUMERIC, $output_items );
                }
            }

            // display output
            foreach ( $output_items as $output_item ) {
                echo $output_item['output'];
            }

        ?></div><!-- authors-list-items --><?php

        if ( $settings['pagination'] == 'yes' && $settings['amount'] ) :

            ?><div class="authors-list-pagination"><?php
                $current_page = max(1, get_query_var('paged'));
                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => 'page/%#%/',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_next'    => false,
                    'type'         => 'list',
                ));
            ?></div><!-- .authors-list-pagination --><?php

        endif;
        
        $output = ob_get_contents();
        ob_end_clean();

        return $output;

    } add_shortcode( 'authors_list', 'authors_list_sc' );

endif; // end if function exists

if ( ! function_exists( 'authors_list_parse_vars' ) ) {

    /**
     * Replace {var} with user meta
     * 
     * @since 1.0.2
     */
    function authors_list_parse_vars( $user_id, $text ) {

        $text = preg_replace_callback( '/{al:([^\s]+)}/i', function( $matches ) use ( $user_id ){
            return authors_list_get_meta( $user_id, $matches[1] );
        }, $text );

        $text = preg_replace_callback( '/\{alf:([^}]+)\}/i', function( $matches ) use ( $user_id ){
            
            // no match
            if ( empty( $matches[1]) ) return;

            // get all data in an array
            $data = explode( ' ', $matches[1] );
            
            // no match for func name
            if ( empty( $data[0] ) ) return;
            
            // get function name
            $function_name = 'authors_list_display_' . $data[0];

            // no function by that name, return
            if ( ! function_exists( $function_name ) ) return;

            // any args?
            $function_args = array(
                'user_id' => $user_id,
            );
            unset( $data[0] );
            if ( count( $data ) > 0 ) {
                foreach ( $data as $data_item ) {
                    $data_item_args = explode( '=', $data_item );
                    if ( ! empty( $data_item_args[0] ) && ! empty( $data_item_args[1] ) ) {
                        $function_args[$data_item_args[0]] = trim( $data_item_args[1],"'" );
                    }

                }
            }

            return call_user_func( $function_name, $function_args );

        }, $text );

        return $text;

    }

}

if ( ! function_exists( 'authors_list_get_meta' ) ) {

    /**
     * Get meta field value
     * 
     * @since 1.0.2
     */
    function authors_list_get_meta( $user_id = false, $name = false ) {

        // no user ID and meta field supplied
        if ( ! $user_id || ! $name ) {
            return;
        }

        // get user meta
        $value = get_user_meta( $user_id, $name, true );        

        // no user meta, try userdata
        if ( ! $value ) {
            $user_data = get_userdata( $user_id );
            if ( ! empty( $user_data->data->$name ) ) {
                $value = $user_data->data->$name;
            }
        }

        // return the value
        return $value;

    }

}

if ( ! function_exists( 'authors_list_display_posts') ) {

    /**
     * Display author posts
     * 
     * @since 1.0.4
     */
    function authors_list_display_posts( $args = array() ) {

        if ( empty( $args['amount'] ) ) {
            $args['amount'] = 1;
        }

        if ( empty( $args['type'] ) ) {
            $args['type'] = 'list';
        }

        if ( empty( $args['show_date'] ) ) {
            $args['show_date'] = 'no';
        }

        if ( empty( $args['date_format'] ) ) {
            $args['date_format'] = get_option( 'date_format' );
        }

        if ( empty( $args['categories'] ) ) {
            $args['categories'] = '';
        } else {
            $args['categories'] = explode( ',', $args['categories' ] );
        }

        // get posts
        $query_args = array(
            'author' => $args['user_id'],
            'numberposts' => $args['amount'],
        );

        if ( ! empty( $args['categories'] ) ) {
            $query_args['category__in'] = $args['categories'];
        }

        $posts = get_posts( $query_args );

        // no posts found, return
        if ( ! $posts ) {
            return;
        }

        $el_wrap = 'ul';
        $el_item = 'li';

        if ( $args['type'] == 'plain' ) {
            $el_wrap = 'div';
            $el_item = 'div';
        }

        // output buffer
        ob_start();
        ?>
            <<?php echo $el_wrap; ?> class="authors-list-posts">
                <?php foreach ( $posts as $post ) : ?>
                    <?php $post_date = '<span>' . get_the_time( $args['date_format'], $post ) . '</span><br>'; ?>
                    <<?php echo $el_item; ?> class="authors-list-posts-item"><a href="<?php echo get_permalink( $post->ID ); ?>"><?php if ( $args['show_date'] == 'yes' ) echo $post_date; ?><?php echo get_the_title( $post->ID ); ?></a></<?php echo $el_item; ?>>
                <?php endforeach; ?>
            </<?php echo $el_wrap; ?>>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // return output
        return $output;

    }

}

if ( ! function_exists( 'authors_list_display_social') ) {

    /**
     * Display author social
     * 
     * @since 1.0.8
     */
    function authors_list_display_social( $args = array() ) {

        $user_id = $args['user_id'];

        if ( empty( $args['type'] ) ) {
            $args['type'] = 'svg';
        }

        $urls = array();

        $urls['facebook'] = get_user_meta( $user_id, 'facebook', true );
        $urls['instagram'] = get_user_meta( $user_id, 'instagram', true );
        $urls['linkedin'] = get_user_meta( $user_id, 'linkedin', true );
        $urls['pinterest'] = get_user_meta( $user_id, 'pinterest', true );
        $urls['tumblr'] = get_user_meta( $user_id, 'tumblr', true );
        $urls['twitter'] = get_user_meta( $user_id, 'twitter', true );
        $urls['youtube'] = get_user_meta( $user_id, 'youtube', true );

        if ( ! empty( $urls['twitter'] ) ) {
            $urls['twitter'] = 'http://twitter.com/' . $urls['twitter'];
        }

        $user_data = get_userdata( $user_id );
        if ( ! empty( $user_data->user_url ) ) {
            $urls['website'] = $user_data->user_url;
        }

        $icons = array();
        
        if ( $args['type'] == 'svg' ) {
            $icons['facebook']  = '<i class="fab fa-facebook-f"></i>';
            $icons['instagram'] = '<i class="fab fa-instagram"></i>';
            $icons['linkedin']  = '<i class="fab fa-linkedin"></i>';
            $icons['pinterest'] = '<i class="fab fa-pinterest"></i>';
            $icons['tumblr']    = '<i class="fab fa-tumblr"></i>';
            $icons['twitter']   = '<i class="fab fa-twitter"></i>';
            $icons['youtube']   = '<i class="fab fa-youtube"></i>';
            $icons['website']   = '<i class="fa fa-link"></i>';
        } elseif ( $args['type'] == 'fontawesome-v4' ) {
            $icons['facebook']  = '<i class="fa fa-facebook"></i>';
            $icons['instagram'] = '<i class="fa fa-instagram"></i>';
            $icons['linkedin']  = '<i class="fa fa-linkedin"></i>';
            $icons['pinterest'] = '<i class="fa fa-pinterest"></i>';
            $icons['tumblr']    = '<i class="fa fa-tumblr"></i>';
            $icons['twitter']   = '<i class="fa fa-twitter"></i>';
            $icons['youtube']   = '<i class="fa fa-youtube"></i>';
            $icons['website']   = '<i class="fa fa-link"></i>';
        } elseif ( $args['type'] == 'fontawesome-v5' ) {
            $icons['facebook']  = '<i class="fab fa-facebook"></i>';
            $icons['instagram'] = '<i class="fab fa-instagram"></i>';
            $icons['linkedin']  = '<i class="fab fa-linkedin"></i>';
            $icons['pinterest'] = '<i class="fab fa-pinterest"></i>';
            $icons['tumblr']    = '<i class="fab fa-tumblr"></i>';
            $icons['twitter']   = '<i class="fab fa-twitter"></i>';
            $icons['youtube']   = '<i class="fab fa-youtube"></i>';
            $icons['website']   = '<i class="fa fa-link"></i>';
        }

        // output buffer
        ob_start();
        ?>
        <div class="authors-list-item-social">
            <?php foreach ( $urls as $site => $url ) : ?>
                <?php if ( ! empty( $url ) ) : ?>
                    <a target="_blank" rel="nofollow external noopener noreferrer" href="<?php echo esc_url( $url ); ?>" class="authors-list-item-social-<?php echo esc_attr( $site ); ?>">
                     <!--   <?php if ( $args['type'] == 'svg' ) : ?><svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><?php endif; ?> -->
                            <?php echo $icons[$site]; ?>
                        <?php if ( $args['type'] == 'svg' ) : ?></svg><?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // return output
        return $output;

    }

}

if ( ! function_exists( 'authors_list_buddypress_follow_link') ) {

    /**
     * Display follow link buddypress
     * 
     * @since 1.1.5
     */
    function authors_list_display_buddypress_follow_link( $args = array() ) {

        $user_id = $args['user_id'];

        if ( function_exists( 'bp_follow_add_follow_button' ) && bp_loggedin_user_id() && bp_loggedin_user_id() != $user_id ) {
            bp_follow_add_follow_button( array(
                'leader_id'   => $user_id,
                'follower_id' => bp_loggedin_user_id()
            ) );
        }

    }

}

if ( ! function_exists( 'authors_list_display_role') ) {

    /**
     * Display user role
     * 
     * @since 1.1.7
     */
    function authors_list_display_role( $args = array() ) {

        $user_id = $args['user_id'];

        $roles = get_userdata( $user_id );
        $roles = $roles->roles;

        // output buffer
        ob_start();
        ?>
        <ul class="authors-list-item-roles">
            <?php foreach ( $roles as $role ) : ?>
                <li><?php echo $role; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // return output
        return $output;

    }

}

if ( ! function_exists( 'authors_list_display_link') ) {

    /**
     * Display link to
     * 
     * @since 1.1.7
     */
    function authors_list_display_link( $args = array() ) {

        $user_id = $args['user_id'];
        
        if ( empty( $args['url'] ) ) {
            $args['url'] = 'archive';
        }

        if ( $args['url'] == 'archive' ) {
            $url = get_author_posts_url( $user_id );
            $text = 'View Posts';
        } else if ( $args['url'] == 'bbpress_profile' && function_exists( 'bbp_get_user_profile_link' ) ) {
            $url = bbp_get_user_profile_url( $user_id );
            $text = 'View Profile';
        } else if ( $args['url'] == 'buddypress_profile' && function_exists( 'bp_core_get_user_domain' ) ) {
            $url = bp_core_get_user_domain( $user_id );
            $text = 'View Profile';
        } else {
            $url = esc_url( $args['url'] );
            $text = $url;
        }

        // output buffer
        ob_start();
        ?>
            <a href="<?php echo esc_url( $url ); ?>"><?php echo $text; ?></a>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // return output
        return $output;

    }

}

if ( ! function_exists( 'authors_list_display_latest_post_date') ) {

    /**
     * Display latest post date
     * 
     * @since 1.2.3
     */
    function authors_list_display_latest_post_date( $args = array() ) {

        if ( empty( $args['format'] ) ) {
            $args['format'] = get_option( 'date_format' );
        }

        if ( empty( $args['link'] ) ) {
            $args['link'] = 'yes';
        }

        // get posts
        $posts = get_posts(array(
            'author' => $args['user_id'],
            'numberposts' => 1,
        ));

        // no posts found, return
        if ( ! $posts ) {
            return;
        }

        // output buffer
        ob_start();
        ?>
            <div class="authors-list-latest-post-date">
                <?php foreach ( $posts as $post ) : ?>
                    <?php if ( $args['link'] == 'yes' ) : ?><a href="<?php echo get_permalink( $post->ID ); ?>"><?php endif; ?>
                        <?php echo get_the_time( $args['format'], $post ); ?>
                    <?php if ( $args['link'] == 'yes' ) : ?></a><?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // return output
        return $output;

    }

}