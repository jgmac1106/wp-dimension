<?php get_header(); ?>

			<!-- Header -->
				<header id="header" class="h-card">
					
					<div class="logo">
	
	
					<?php if ( get_theme_mod( 'dimension_logo' ) ) : ?>
						<img class="u-photo" src="<?php echo esc_url( get_theme_mod( 'dimension_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
					<?php else : ?>
						<span class="icon fa-cogs"></span>
					<?php endif; ?>
					
					</div>
					
					<div class="content">
						<div class="inner">
							
							<h1 class="p-name"><?php bloginfo( 'name' ); ?><link a class="u-url" href="https://indieweb.jgregorymcverry.com/blog/" rel="me"></h1>
							
							<?php if ( get_bloginfo( 'description' )  !== '' ) { ?>
								<h2 class="p-note"><?php bloginfo( 'description' ); ?></h2>
								<!-- If you are using an email, title , or organization you may want to change your h-card. Please see http://microformats.org/wiki/h-card for more info --!>
							<?php } ?>
							
							<!-- begin front quote (if used) -->
							<?php dimension_quote_text()?>
							<!-- end front quote -->
							
							<?php 
								if (  has_nav_menu( 'dimension-social' ) ) {
									wp_nav_menu( array( 'theme_location' => 'dimension-social', 'menu_class' => 'dsocial' ) );
								}
								
							?>
						
							</div>
					</div>
					
					
					<?php 
					// custom query for posts listed by menu order
					// exclude Hello World
					
					$exclude_hello_world = array( 1 );
					
					$the_query = new WP_Query(array(
						'post_type' => 'post', 
						'post_status' => 'publish', 
						'post__not_in' => $exclude_hello_world, 
						'orderby' => 'menu_order', 
						'order' => 'ASC',
						'posts_per_page' => 8,
					) );

					// First Loop for the nav
					if ( $the_query->have_posts() ) {
					
						echo '<nav><ul>';
						while ( $the_query->have_posts() ) {
						
							
							$the_query->the_post();
							
							$button_label =  get_post_meta( get_the_ID(), '_button_label', true );
							
							if ( !empty( $button_label ) ) {
								echo '<li><a href="#' . sanitize_title( $button_label ) . '">' . $button_label . '</a></li>';	
							} else {
							
								echo '<li><a href="#' . sanitize_title( get_the_title() ) . '">' . get_the_title() . '</a></li>';
							}
						}
						
						echo '</ul></nav></header>';
						
						// rewind so we can do the content
						$the_query->rewind_posts();
						
						
						// Second Loop for the stuff 
						if ( $the_query->have_posts() ) {
							echo '<!-- Main --><div id="main">';
						
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								
								$button_label =  get_post_meta( get_the_ID(), '_button_label', true );
								$the_link = get_post_meta( get_the_ID(), '_dimension_link', true );
								$fa_icon = get_post_meta( get_the_ID(), '_link_fa_icon', true );
								
								
								if ( !empty( $button_label ) ) {
									echo '<article class="h-entry" id="' . sanitize_title( $button_label ) . '"><div class="p-name"><h2 class="major">' . get_the_title() . '</h2></div>';		
								} else {
									echo '<article class="h-entry" id="' . sanitize_title( get_the_title() ) . '"><div class="p-name"><h2 class="major">' . get_the_title() . '</h2></div>';
								}
								
								if ( has_post_thumbnail() ) {
							
									if ( !empty($the_link) ) {
										echo '<a class="u-url" href="' . $the_link . '"><span class="image main">';
										the_post_thumbnail();
										echo '</span></a>';
									 } else {
										echo '<span class="image main">';
										the_post_thumbnail();
										echo '</span>';
									 }
								} // has post thumbnail
							
								the_content();	
							
								// add the button if we have a URL destination
								if ( !empty( $the_link ) ) {
								
									$go_button_name = ( !empty( get_post_meta( get_the_ID(), '_go_button_name', true ) ) ) ? get_post_meta( get_the_ID(), '_go_button_name', true ) : 'Go';
									
									echo '<p class="align-center"><a class="u-url" href="' . $the_link . '" class="button icon ' . $fa_icon . '">' . $go_button_name .  '</a></p>';
								}
							
							
								edit_post_link('Edit This', '<p class="edit-this"><span class="fa fa-pencil-square-o" aria-hidden="true"></span> ', '</p>');
								echo '</article>';
							} // while
							
						} // second query loop
						
						echo '</div><!-- Main -->';

						/* Restore original Post Data */
						wp_reset_postdata();
					} else {
						echo '<!-- Main --><div id="main-none"><p>To add your links, create some new posts! Hello World does not count ;-)</p></div>';
					}
					?>
								
<?php get_footer(); ?>
