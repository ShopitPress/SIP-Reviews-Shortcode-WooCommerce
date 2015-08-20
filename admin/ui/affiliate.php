<div class="sip-credit-affiliate-link-warp">
  <h2>Be awesome</h2>
  <p>Do you like this plugin? Would you like to see even more great features? Please be awesome and help us maintain and develop free plugins by checking the option below</p>
	
	<form method="post" action="options.php">
	  <?php settings_fields( 'sip-rswc-affiliate-settings-group' ); ?>
	  <?php $options = get_option('sip-rswc-affiliate-radio'); ?>
			<label><input  id="spc-rswc-affiliate-checkbox" type="checkbox" name="sip-rswc-affiliate-check-box" value="true" <?php echo esc_attr( get_option('sip-rswc-affiliate-check-box', false))?' checked="checked"':''; ?> /> Yes, I want to help development of this plugin</label><br />
			<div id="spc-rswc-diplay-affiliate-toggle">

				<label><input id="spc-rswc-discreet-credit" type="radio" name="sip-rswc-affiliate-radio[option_three]" value="value1"<?php checked( 'value1' == $options['option_three'] ); ?> checked/> Add a discreet credit</label><br />
				<label><input id="spc-rswc-affiliate-link" 	type="radio" name="sip-rswc-affiliate-radio[option_three]" value="value2"<?php checked( 'value2' == $options['option_three'] ); ?> /> Add my affiliate link</label><br />
				<div id="spc-rswc-affiliate-link-box">
					<label><input type="text" name="sip-rswc-affiliate-affiliate-username" value="<?php echo esc_attr( get_option('sip-rswc-affiliate-affiliate-username')) ?>" /> Input affiliate username/ID</label><br />
				</div>
            <p class="sip-text">Make money recommending our plugins. Register for an affiliate account at <a href="https://shopitpress.com/affiliate-area/?utm_source=wordpress.org&amp;utm_medium=affiliate&amp;utm_campaign=sip-reviews-shortcode-woocommerce" target="_blank">Shopitpress</a>.
            </p>
					</a>
			</div>
		<?php submit_button(); ?>
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){

		jQuery("#spc-rswc-diplay-affiliate-toggle").hide();
		jQuery("#spc-rswc-affiliate-link-box").hide();

		if (jQuery('#spc-rswc-affiliate-checkbox').is(":checked"))
		{
		  jQuery("#spc-rswc-diplay-affiliate-toggle").show('slow');
		}

		jQuery('#spc-rswc-affiliate-checkbox').click(function() {
		  jQuery('#spc-rswc-diplay-affiliate-toggle').toggle('slow');
		})

		if (jQuery('#spc-rswc-affiliate-link').is(":checked"))
		{
		  jQuery("#spc-rswc-affiliate-link-box").show('slow');
		}

		jQuery('#spc-rswc-affiliate-link').click(function() {
		  jQuery('#spc-rswc-affiliate-link-box').show('slow');
		})

		jQuery('#spc-rswc-discreet-credit').click(function() {
		  jQuery('#spc-rswc-affiliate-link-box').hide('slow');
		})

	});
</script>
