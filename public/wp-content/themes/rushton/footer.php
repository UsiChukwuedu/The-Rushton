
	<?php wp_footer(); ?>
	<?php if (displayTrackingCode()) {
		echo get_field('tracking_code_before_body', 'option');
	} ?>
	<script>
        // REPLACE WITH ONLY COMPONENTS USED - ex. $(document).foundation('section navigation');
		$(document).foundation();
	</script>

	<script type="text/javascript" charset="utf-8">
		var _uf = _uf || {};
		_uf.domain = ".<?php echo str_replace('www.', '', $_SERVER['HTTP_HOST']); ?>";
	</script>
	<script src="<?php echo THEME_URL; ?>/js/vendor/utm_form-1.0.4.min.js" async></script>
	<?php echo $GLOBALS['checkboxRequiredScript']; ?>
	<script>
      var swiper = new Swiper(".mySwiper", {
        spaceBetween: 0,
        centeredSlides: true,
        autoplay: {
          delay: 7500,
          disableOnInteraction: false,
        },
      });
    </script>
	<?php if(get_field('bLazy')): ?>
		<script src="blazy.min.js"></script>
		<script>
		var bLazy = new Blazy({
			offset: 150,
			loadInvisible: true,
			success: function (ele) {
				// new Foundation.Equalizer($('.eqq')).applyHeight();
				// if ($('body').hasClass('page-home')) {
				// --- home --- //
				// } else if ($('body').hasClass('blog')) {
				// --- blog --- //
				// }
			}
		});
		</script>
	<?php endif; ?>
</body>
</html>
