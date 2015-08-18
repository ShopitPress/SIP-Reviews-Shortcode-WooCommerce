<hr>
<form method="post" action="options.php">
  <?php settings_fields( 'sip-rswc-affiliate-settings-group' ); ?>
  <?php $options = get_option('sip-rswc-affiliate-radio'); ?>
		<label><input  id="spc-rswc-affiliate-checkbox" type="checkbox" name="sip-rswc-affiliate-check-box" value="true" <?php echo esc_attr( get_option('sip-rswc-affiliate-check-box', false))?' checked="checked"':''; ?> /> I want to help development of this plugin</label><br />
		<div id="spc-rswc-diplay-affiliate-toggle">

			<label><input id="spc-rswc-discreet-credit" type="radio" name="sip-rswc-affiliate-radio[option_three]" value="value1"<?php checked( 'value1' == $options['option_three'] ); ?> checked/> Add a discreet credit</label>
			<label><input id="spc-rswc-affiliate-link" 	type="radio" name="sip-rswc-affiliate-radio[option_three]" value="value2"<?php checked( 'value2' == $options['option_three'] ); ?> /> Add my affiliate link</label><br />
			<div id="spc-rswc-affiliate-link-box">
				<label><input type="text" name="sip-rswc-affiliate-affiliate-username" value="<?php echo esc_attr( get_option('sip-rswc-affiliate-affiliate-username')) ?>" /> Input affiliate username/ID</label><br />			
			</div>
			<p>Make money recommending our plugins. Register for an affiliate account at 
		
		<?php if( 'value1' == $options['option_three'] ) { $url = "https://shopitpress.com/affiliate-area/utm_source=wordpress.org&utm_medium=affiliate&utm_campaign=sip-reviews-shortcode-woocommerce" ; } ?>
		<?php if( 'value2' == $options['option_three'] ) { $url = "https://shopitpress.com/?offer=". esc_attr( get_option('sip-rswc-affiliate-affiliate-username')) ; } ?>		
				<a href="<?php echo $url ?>" target="_blank">
					<img src="<?php echo SIP_RSWC_URL ?>admin/assets/images/mini-logo.png">
				</a>

			</p>

		</div>	
	<?php submit_button(); ?>
</form>

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