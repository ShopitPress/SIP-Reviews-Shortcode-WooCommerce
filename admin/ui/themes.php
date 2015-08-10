<?php 

$src_image = SIP_RS_URI . 'admin/assets/images/';

$extensions = array(
    '1' => (object) array(
        'image_url' => $src_image . 'icon-wpgumby.png',
        'url'       => SIP_WPGUMBY_THEME_URL . '?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RS_VERSION .'&utm_campaign=' .SIP_RS_UTM_CAMPAIGN,
        'title'     => SIP_WPGUMBY_THEME,
        'desc'      => __( 'Flat and responsive WooCommerce theme.<br>', 'sip-social-proof' ),
    ),
	);

?>


<div id="shopitpress-wraper">

<?php 
    $i = 0;
    foreach ( (array) $extensions as $i => $extension ) {
        // Attempt to get the plugin basename if it is installed or active.
        $image_url   = $extension->image_url ;
        $url 		 = $extension->url ;
        $title		 = $extension->title ;
        $description = $extension->desc ; 
 		?>
		<div class="shopitpress-addon">
        <h1><?php echo $title ?></h1>
        <p><?php echo $description ?></p>
			<img class="shopitpress-addon-thumb" src="<?php echo $image_url; ?>" width="300px" height="250px" alt="<?php echo $title; ?>">
			<div class="shopitpress-addon-action">
				<a class="active-addon button button-primary " title="<?php echo $title; ?>" href="<?php echo $url; ?>" target="_blank">Learn more</a>
			</div>
		</div> <!-- .shopitpress-addon -->
		<?php $i++; 
	} 
?>
</div><!-- .shopitpress -->