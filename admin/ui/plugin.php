<?php 

$src_image = SIP_RS_URI . 'admin/assets/images/';

$extensions = array(
    '1' => (object) array(
        'image_url' => $src_image . 'icon-social-proof.png',
        'url'       => SIP_SP_PLUGIN_URL . '?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RS_VERSION .'&utm_campaign=' .SIP_RS_UTM_CAMPAIGN,
        'title'     => SIP_SP_PLUGIN,
        'desc'      => __( 'Display real time proof of your sales and customers.<br>', 'sip-social-proof' ),
    ),
    '2' => (object) array(
        'image_url' => $src_image . 'icon-woobundler.png',
        'url'       => SIP_WB_PLUGIN_URL . '?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RS_VERSION .'&utm_campaign=' .SIP_RS_UTM_CAMPAIGN,
        'title'     => SIP_WB_PLUGIN,
        'desc'      => __( 'Bundle maker with real time offers.<br><br>', 'sip-social-proof' ),
    ),
    '3' => (object) array(
        'image_url' => $src_image . 'icon-wooreviews.png',
        'url'       => SIP_WR_PLUGIN_URL . '?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RS_VERSION .'&utm_campaign=' .SIP_RS_UTM_CAMPAIGN,
        'title'     => SIP_WR_PLUGIN,
        'desc'      => __( 'Display product reviews in any post/page with a shortcode.', 'sip-social-proof' ),
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