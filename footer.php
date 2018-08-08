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
				<div class="col-sm">
					<div class="site-info">
						&copy; <?php echo date('Y') . ' ' . __('itransact Media') ?>
					</div><!-- .site-info -->
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
