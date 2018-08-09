<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package itransact_Media
 */

?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="row">
				<?php if ( is_active_sidebar( 'footer_widgets' ) ) : ?>
					<?php dynamic_sidebar( 'footer_widgets' ); ?>
				<?php endif; ?>
			</div>
			<div class="row">
				<div class="col">
					<?php
						$social_sites = ct_itransact_social_array();

						foreach ( $social_sites as $social_site => $profile ) {
							if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
								$active_sites[ $social_site ] = $social_site;
							}
						}

						if ( ! empty( $active_sites ) ) {
							echo '<ul class="social">';
							foreach ( $active_sites as $key => $active_site ) {
										$class = 'fa fa-' . $active_site; ?>
								<li class="<?php echo esc_attr( $active_site ); ?>">
									<a target="_blank" href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
										<i class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $active_site ); ?>"></i>
									</a>
								</li>
							<?php }
							echo "</ul>";
						}
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<div class="site-info">
						&copy; <?php echo date('Y') . ' ' . __('itransact Media') ?>
					</div><!-- .site-info -->
				</div>
			</div>
			<div class="row d-block d-sm-none">
				<div class="col-sm">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'legal',
							'menu_id'        => 'legal-menu',
							'menu_class'		 => 'nav justify-content-center'
						) );
					?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

</body>
</html>
