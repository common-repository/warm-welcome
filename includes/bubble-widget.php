<?php
add_action('wp_head', 'warmwelcome_hook_bubble_widget');
function warmwelcome_hook_bubble_widget() {
	$bubble_widget = carbon_get_theme_option('crb_default_bubble_widget');
	$bubble_widget_post_meta = carbon_get_the_post_meta('ww_bubble_widget');

	if( !$bubble_widget || !$bubble_widget_post_meta ) {
		return;
	}
	?>
	<script>
		window.WIDGET_CONFIG = {
			globalWidgetId: "<?php echo get_option('ww_global_widget_id'); ?>",
			baseUrl: "<?php echo WARMWELCOME_API_URL; ?>",
		}
	</script>
    <?php
}