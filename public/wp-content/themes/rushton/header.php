<?php $GLOBALS['checkboxRequiredScript'] = ""; ?><!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en-US" prefix="og: http://ogp.me/ns#" > <!--<![endif]-->
<!--[if IE 9]> <style>select {background-image:none !important;height:2.5rem !important;padding-right:5px !important;}</style> <![endif]-->
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>/img/favicon.png" type="image/png">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NM4D566V5H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NM4D566V5H');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5QL9Z3R');</script>
<!-- End Google Tag Manager -->

  <title>
    <?php
      /*
        * Print the <title> tag based on what is being viewed.
        */
      global $page, $paged;
      
      wp_title( '|', true, 'right' );

      // Add the blog name.
      //bloginfo( 'name' );

      // Add the blog description for the home/front page.
      $site_description = get_bloginfo( 'description', 'display' );
      if ( $site_description && ( is_home() || is_front_page() ) )

      // Add a page number if necessary:
      if ( $paged >= 2 || $page >= 2 )
          echo ' | ' . sprintf( __( 'Page %s', THEME_TEXTDOMAIN ), max( $paged, $page ) );

      echo "$site_description";
    ?>
  </title>
  <?php /* FONT AWESEOME ---
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-TXfwrfuHVznxCssTxWoPZjhcss/hp38gEOH8UPZG/JcXonvBQ6SlsIF49wUzsGno" crossorigin="anonymous">
  --- */ ?>
  
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
	
  <?php wp_head(); ?>

  <?php if (displayTrackingCode()) {
    echo get_field('tracking_code_before_head', 'option');
  } ?>

  <script>
    var theme_url = '<?php echo THEME_URL; ?>';
    var site_url = '<?php echo site_url(); ?>';
  </script>
</head>


<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5QL9Z3R"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- 
<div id="bigcover"></div>
<div id="to-top">
  <i class="fa fa-chevron-up" aria-hidden="true"></i>
</div> -->