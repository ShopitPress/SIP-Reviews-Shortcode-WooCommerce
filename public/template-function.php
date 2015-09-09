<?php

	/**
	 * To chek the woocmmerece is active or not
	 *
	 * If active then add action on the following functions.
	 *
	 * @since      1.0.0
	 *
	 * @package    Sip_Reviews_Shortcode_Woocommerce
	 * @subpackage Sip_Reviews_Shortcode_Woocommerce/public/
	 */
	if ( in_array ( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){

		add_action ( 'media_buttons_context','sip_rswc_tinymce_media_button' );
		add_action ( 'admin_footer','sip_rswc_media_button_popup' );
		add_action ( 'admin_footer','sip_rswc_add_shortcode_to_editor' );
		add_shortcode ('woocommerce_reviews', 'sip_review_shortcode_wc' );
		add_action( 'admin_init',  'sip_rswc_settings_init'  );
		add_action( 'admin_enqueue_scripts' ,  'sip_rswc_add_styles_scripts' );
		add_action( 'admin_init', 'sip_rswc_affiliate_register_admin_settings' );
	}


	 /**
	 * registers credit/affiliate link options
	 *
	 *
	 * @since      1.0.1
	 */
	function sip_rswc_affiliate_register_admin_settings() {
		register_setting( 'sip-rswc-affiliate-settings-group', 'sip-rswc-affiliate-check-box' );
		register_setting( 'sip-rswc-affiliate-settings-group', 'sip-rswc-affiliate-radio' );
		register_setting( 'sip-rswc-affiliate-settings-group', 'sip-rswc-affiliate-affiliate-username' );
	}


	/**
	 * TO get aggregate rating
	 *
	 * @since    	1.0.0
	 * @return 		int
	 */
	function wc_product_reviews_pro_get_product_rating_count( $product_id, $rating = null ) {
		global $wpdb;
		$where_meta_value 	= $rating ? $wpdb->prepare( " AND meta_value = %d", $rating ) : " AND meta_value > 0";
		$count 							= $wpdb->get_var( $wpdb->prepare("
														SELECT COUNT(meta_value) FROM $wpdb->commentmeta
														LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
														WHERE meta_key = 'rating'
														AND comment_post_ID = %d
														AND comment_approved = '1'
														", $product_id ) . $where_meta_value );
		return $count;
	}

	/**
	 * Sortcode function Template
	 *
	 * @since    	1.0.0
	 */
	function sip_review_shortcode_wc( $atts ) {
  	global $post,$wpdb;
  	extract( shortcode_atts(
			array(
				'id' 							=> '',
				'no_of_reviews' 	=> '',
				'product_title' 	=> '',
			), $atts )
		);

	  // if number of review not mention in shor coode then defaul value will be assign
		if( $no_of_reviews == "" ){
			$no_of_reviews = 5;
		}

  	// if product title is not mention by user in shortcode then get default value
		if( $product_title == "" ){
			$query 					= "SELECT post_title FROM {$wpdb->prefix}posts p WHERE p.ID = {$id} AND p.post_type = 'product' AND p.post_status = 'publish'";
			$product_title 	= $wpdb->get_var( $query );
		}

		$options = get_option( 'color_options' );
	  $star_color = ( $options['star_color'] != "" ) ? sanitize_text_field( $options['star_color'] ) : '';
	  $bar_color = ( $options['bar_color'] != "" ) ? sanitize_text_field( $options['bar_color'] ) : '#AD74A2';

	  ?>
	  	<style type="text/css">.star-rating:before, .woocommerce-page .star-rating:before, .star-rating span:before {color: <?php echo $star_color; ?>;}</style>
	  <?php

	  if( $star_color != "")
	  	$star_color = "style='color:". $star_color .";'";

	  if( $bar_color != "")
	  	$bar_color = "background-color:".$bar_color .";";


		// To check that post id is product or not
		if( get_post_type( $id ) == 'product' ) {
			ob_start();
			// to get the detail of the comments etc aproved and panding status
			$comments_count = wp_count_comments( $id );
			?>
			<div id="wc-reviews-shortcode" style="display:block">
				<div class="onepcssgrid-1000">
					<div class="onerow">
						<div class="col12">
							<div id="reviews">
								<div class="without-pro">
									<div class="product-rating">
										<div class="product-rating-summary">

											<?php //It calculate the rating for each star ?>
											<?php $ratings 	= array( 5, 4, 3, 2, 1 ); ?>
											<?php $result		=	0; ?>
											<?php foreach ( $ratings as $rating ) : ?>
											<?php
											if($comments_count->approved>0){
												$count 				= wc_product_reviews_pro_get_product_rating_count( $id, $rating );
												$percentage 	= $count * $rating / $comments_count->approved ;
												$result 			= $result + $percentage;
											}
											?>
											<?php endforeach; ?>

											<!-- it is not for display it is only to generate schema for goolge search result -->
											<div itemscope itemtype="http://schema.org/Product"  style="display:none;">
												<span itemprop="name"><?php echo $product_title; ?></span>
												<div class="star_container" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
													<span itemprop="itemReviewed"><?php echo $product_title; ?></span>
													<span itemprop="ratingValue"><?php echo number_format($result, 2); ?></span>
													<span itemprop="bestRating">5</span>
													<span itemprop="reviewcount" style="display:none;"><?php echo $comments_count->approved ?></span>
												</div>
												<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
   												<span itemprop="priceCurrency" content="<?php $currency = get_woocommerce_currency(); echo $currency; ?>"><?php echo get_woocommerce_currency_symbol($currency) ?></span>
   												<span itemprop="price" content="<?php $get_price = get_post_meta( $id , '_price' ); echo $get_price[0]; ?>"><?php echo get_woocommerce_currency_symbol(); echo $get_price[0]; ?></span>
    										</div>
											</div>


											<h3><?php echo number_format( $result, 2 ); ?> out of 5 stars</h3>
											<p class="rswc-front-review"><?php echo $comments_count->approved ?>
												<span class="review-icon-image">reviews		
													<?php if(get_option('sip-rswc-affiliate-check-box') == "true") { ?>
														<?php $options = get_option('sip-rswc-affiliate-radio'); ?>
														<?php if( 'value1' == $options['option_three'] ) { $url = "https://shopitpress.com/?utm_source=referral&utm_medium=credit&utm_campaign=sip-reviews-shortcode-woocommerce" ; } ?>
														<?php if( 'value2' == $options['option_three'] ) { $url = "https://shopitpress.com/?offer=". esc_attr( get_option('sip-rswc-affiliate-affiliate-username')) ; } ?>
														<a class="sip-rswc-credit" href="<?php echo $url ; ?>" target="_blank" data-tooltip="These reviews were created with SIP Reviews Shortcode Plugin"></a>
													<?php } ?>
												</span>
											</p>
										</div>

										<!-- it will show the table at the top -->
										<div class="product-rating-details">
											<table>
												<?php $count 			= 0 ; ?>
												<?php $percentage = 0 ; ?>
												<?php foreach ( $ratings as $rating ) : ?>
													<?php
													if( $comments_count->approved > 0 ) {
														$count 				= wc_product_reviews_pro_get_product_rating_count( $id, $rating );
														$percentage 	= $count / $comments_count->approved * 100;
													}
													?>
													<?php $url = get_permalink(); ?>
													<tr>
														<td class="rating-number">
															<a href="<?php echo $url; ?>#comments" <?php echo $star_color; ?>><?php echo $rating; ?> <span class="rating-star"></span></a>
														</td>
														<td class="rating-graph">
															<a href="<?php echo $url; ?>#comments" class="bar" style="float:left; <?php echo $bar_color; ?> width: <?php echo $percentage; ?>%" title="<?php printf( '%s%%', $percentage ); ?>"></a>
														</td>
														<td class="rating-count">
															<a href="<?php echo $url; ?>#comments" <?php echo $star_color; ?>><?php echo $count; ?></a>
														</td>
													</tr>
												<?php endforeach; ?>
											</table>
										</div>

									</div><!-- .product-rating -->
								</div><!-- .without-pro -->
							</div><!-- #reviews -->

						</div><!-- .col12 -->
					</div><!-- .row -->

					<!-- It will show the comments -->
					<div class="onerow">
						<div class="col12">
							<div class="custom_products_reviews">
								<!-- to call the list of the comments -->
								<?php woocommerce_print_reviews( $id , $product_title , $no_of_reviews); ?>
							</div>
						</div><!-- .col12 -->
					</div><!-- .row -->
				</div><!-- .container -->
			</div>
			<div style="clear:both"></div>
			<?php
			return ob_get_clean();
		}// end of post id is product or not
	}

	/**
	 * To get full comments
	 *
	 * @since    	1.0.0
	 * @return 		string 	it is return the full lenght of comments lenght limit is 2000 chracters.
	 */
	function get_comment_excerpt_full( $comment_ID = 0 ) {
    $comment 			= get_comment( $comment_ID );
    $comment_text = strip_tags( $comment->comment_content );
    $blah 				= explode( ' ', $comment_text );

    if ( count( $blah ) > 2000 ) {
        $k = 2000;
        $use_dotdotdot = 1;
    } else {
        $k = count( $blah );
        $use_dotdotdot = 0;
    }

    $excerpt = '';
    for ( $i = 0; $i < $k; $i++ ) {
        $excerpt .= $blah[$i] . ' ';
    }
    $excerpt .= ($use_dotdotdot) ? '&hellip;' : '';

    return apply_filters( 'get_comment_excerpt', $excerpt, $comment_ID, $comment );
	}

	/**
	 * To get limited text comments to dispaly
	 *
	 * @since    	1.0.0
	 * @return 		string 	it is return the 35 chracters of comments
	 */
	function get_comment_excerpt_trim( $comment_ID = 0 ) {
    $comment 			= get_comment( $comment_ID );
    $comment_text = strip_tags($comment->comment_content);
    $blah 				= explode( ' ', $comment_text );

    if ( count ( $blah ) > 35 ) {
        $k = 35;
        $use_dotdotdot = 1;
    } else {
        $k = count( $blah );
        $use_dotdotdot = 0;
    }

    $excerpt = '';
    for ( $i = 0; $i < $k; $i++ ) {
        $excerpt .= $blah[$i] . ' ';
    }
    $excerpt .= ($use_dotdotdot) ? '<a style="cursor:pointer" id="comment-'.$comment_ID.'">&nbsp;Read More</a>' : '';

    return apply_filters( 'get_comment_excerpt', $excerpt, $comment_ID, $comment );
	}

	/**
	 * To give complete list of comments in ul tag, it ie printing the all data of li
	 *
	 * @since    	1.0.0
	 * @return 		string , mixed html string in $out_reviews
	 */
	function woocommerce_print_reviews( $id = "" , $title="" , $no_of_reviews=5 ) {
		?>
			<!-- it is for load more comments -->
				<script type="text/javascript">
					jQuery(document).ready(function () {
				    size_li_<?php echo $id; ?> = jQuery(".commentlist_<?php echo $id; ?> li").size();
				    
				    x_<?php echo $id; ?> = <?php echo $no_of_reviews; ?>;
				    jQuery('.commentlist_<?php echo $id; ?> li:lt('+x_<?php echo $id; ?>+')').show();
				    if( size_li_<?php echo $id; ?> <= x_<?php echo $id; ?>  ){
				        jQuery('#load_more_<?php echo $id; ?>').hide();
				      }
				    jQuery('#load_more_<?php echo $id; ?>').click(function () {
				        x_<?php echo $id; ?> = (x_<?php echo $id; ?> + <?php echo $no_of_reviews; ?> <= size_li_<?php echo $id; ?>) ? x_<?php echo $id; ?> + <?php echo $no_of_reviews; ?> : size_li_<?php echo $id; ?>;
				        jQuery('.commentlist_<?php echo $id; ?> li:lt('+ x_<?php echo $id; ?> +')').show();
				        if( x_<?php echo $id; ?>  == size_li_<?php echo $id; ?> ){
				          jQuery('#load_more_<?php echo $id; ?>').hide();
				        }
				    });
				});
				</script>
		<?php
		global $wpdb, $post;
		$query 							= 	"SELECT c.* FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c WHERE p.ID = {$id} AND p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0 ORDER BY c.comment_date DESC";
		$comments_products 	= 	$wpdb->get_results($query, OBJECT);
		$out_reviews				= 	"";
		if ( $comments_products ) {
			foreach ( $comments_products as $comment_product ) {
				$id_ 						= 	$comment_product->comment_post_ID;
				$name_author 		= 	$comment_product->comment_author;
				$comment_id  		= 	$comment_product->comment_ID;
				$comment_date  	= 	get_comment_date( 'M d, Y', $comment_id );
				$_product 			= 	get_product( $id_ );
				$rating 				=  	intval( get_comment_meta( $comment_id, 'rating', true ) );
				$rating_html 		= 	$_product->get_rating_html( $rating );
				$user_id	 			=		$comment_product->user_id;
				$votes 					=		"";

				$options = get_option( 'color_options' );
			  $star_color = ( $options['star_color'] != "" ) ? sanitize_text_field( $options['star_color'] ) : '';
			  $load_more_button = ( $options['load_more_button'] != "" ) ? sanitize_text_field( $options['load_more_button'] ) : '';
  			$load_more_text = ( $options['load_more_text'] != "" ) ? sanitize_text_field( $options['load_more_text'] ) : '';

				$review_body_text_color 	= ( $options['review_body_text_color'] != "" ) ? sanitize_text_field( $options['review_body_text_color'] ) : '';
  			$review_background_color 	= ( $options['review_background_color'] != "" ) ? sanitize_text_field( $options['review_background_color'] ) : '';
  			$review_title_color 			= ( $options['review_title_color'] != "" ) ? sanitize_text_field( $options['review_title_color'] ) : '';

			  if( $star_color != "")
	  			$star_color = "style='color:". $star_color .";'";
	  		$button = 'style="';
	  		if( $load_more_button != "")
	  			$button .= 'background-color:'. $load_more_button .';';
	  		if( $load_more_text != "")
	  			$button .= 'color:'. $load_more_text .';';
				$button .= '"';

			  if( $review_title_color != "")
	  			$review_title_color = "style='color:". $review_title_color .";'";

				$review_background = 'style="';
	  		if( $review_background_color != "")
	  			$review_background .= 'background-color:'. $review_background_color .';';
	  		if( $review_body_text_color != "")
	  			$review_background .= 'color:'. $review_body_text_color .';';
				$review_background .= '"';

				// to check the woocommerce review pro plugin is active or not
				// if active then show the vote which is given by user
				$Woo_Reviews_Shortcode = new SIP_Reviews_Shortcode_WC;
				if( $Woo_Reviews_Shortcode->product_reviews_pro() ) {
					$negative_votes =  intval( get_comment_meta( $comment_id, 'negative_votes', true ) );
					$positive_votes =  intval( get_comment_meta( $comment_id, 'positive_votes', true ) );
					$votes 					= '<div class="woocommerce-page">
															<div id="reviews">
																<p class="contribution-actions">
																	<a href="#" class="vote vote-up js-tip" data-comment-id="'.$comment_id.'"></a><span class="vote-count vote-count-positive">('.$positive_votes.')</span>
																	<a href="#" class="vote vote-down js-tip " data-comment-id="'.$comment_id.'"></a><span class="vote-count vote-count-negative">('.$negative_votes.')</span>
																	<span class="feedback"></span>
																	<a href="#flag-contribution-'.$comment_id.'" class="flag js-toggle-flag-form js-tip " data-comment-id="'.$comment_id.'"></a>
																</p>
															</div>
														</div>';
				}

				$out_reviews 		.= '<li itemprop="review" itemscope="" itemtype="http://schema.org/Review" class="review" id="li-comment-'.$comment_id.'">
															<div id="comment-'.$comment_id.'" class="comment_container" '.$review_background.'>
																<!-- only for schema -->
																<div itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/Product" style="display:none;">
																	<span itemprop="name">'.$title.'</span>
																</div>

																<div class="comment-text">
																	<a href="#" '.$star_color. '>'.$rating_html.'</a>
																	<p class="meta">
																		<strong itemprop="author" '.$review_title_color.'>'.$name_author.' -
																			<time itemprop="datePublished" datetime="'.$comment_date.'">'.$comment_date.'</time>
																		</strong>
																	</p>

																	<div itemprop="description" class="description">
																		<div id="hide-'.$comment_id.'">
																			<p>'.nl2br( get_comment_excerpt_trim( $comment_id ) ).'</p>
																		</div>
																		<p style="display:none;" id="comment-'.$comment_id.'-full">'.nl2br( get_comment_excerpt_full( $comment_id ) ).'</p>

																		'.$votes.'
																	</div>
																</div><!-- .comment-text -->
															</div><!-- .comment_container -->
														</li><!-- #li-comment -->';

				$out_reviews 		.= '<!-- click more to read it will expend the text -->
															<script>
																jQuery("#comment-'.$comment_id.'").click(function(){
															    jQuery("#comment-'.$comment_id.'-full").show();
															    jQuery("#hide-'.$comment_id.'").hide();
															   });
															</script>';
			}//end of lop
		} //end of if condition
		if ( $out_reviews != '' ) {
			$out_reviews = '<ul id="comments_list" class="commentlist commentlist_'. $id .'">' . $out_reviews . '</ul><div class="load_more" id="load_more_'. $id .'"><button '. $button .' type="button">Load More</button></div>';
		} else {
			$out_reviews = '<ul class="commentlist"><li><p class="content-comment">'. __('No products reviews.') . '</p></li></ul>';
		}
		echo $out_reviews;
	}

	/**
	 * add the button to the tinymce editor
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_tinymce_media_button( $context ) {
		return $context .= __("<a href=\"#TB_inline?width=180&inlineId=shortcode_popup&width=540&height=153\" class=\"button thickbox\" id=\"shortcode_popup_button\" title=\"Product Reviews\">Product Reviews</a>");
	}

	/**
	 * Generate inline content for the popup window when the "shortcode" button is clicked
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_media_button_popup() { ?>
  	<div id="shortcode_popup" style="display:none;">
    	<div class="wrap">
      	<div>
        	<h2>Insert Product Reviews</h2>
        	<div class="shortcode_add">

        		<table>
        			<tr>
        				<th><label for="woocommerce_review_id">Product ID : </label></th>
	        			<td><input type="text" id="woocommerce_review_id"><br /></td>
	        		</tr>
	        		<tr>
	        			<th><label for="woocommerce_review_title">Product Title : </label></th>
	        			<td><input type="text" id="woocommerce_review_title"><br /></td>
	        		</tr>
	        		<tr>
	        			<th><label for="woocommerce_review_comments">No. of Reviews : </label></th>
	        			<td>
	        				<input type="text" id="woocommerce_review_comments">
	        				<button class="button-primary" id="id_of_button_clicked">Insert Reviews</button>
	        			</td>
	        		</tr>
	        	</table>

	        </div>
	      </div>
	    </div>
	  </div>
	<?php
	}

	/**
	 * javascript code needed to make shortcode appear in TinyMCE edtor
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_add_shortcode_to_editor() { ?>
		<script>
			jQuery('#id_of_button_clicked ').on('click',function(){
			  var shortcode_id 				= jQuery('#woocommerce_review_id').val();
			  var shortcode_title 		= jQuery('#woocommerce_review_title').val();
			  var shortcode_comments 	= jQuery('#woocommerce_review_comments').val();

			  var shortcode = '[woocommerce_reviews id="'+shortcode_id+'"  product_title="'+shortcode_title+'"  no_of_reviews="'+shortcode_comments+'" ]';
			  if( !tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
			    jQuery('textarea#content').val(shortcode);
			  } else {
			    tinyMCE.execCommand('mceInsertContent', false, shortcode);
			  }
			  //close the thickbox after adding shortcode to editor
			  self.parent.tb_remove();
			});
		</script>
		<?php
	}

	/**
	 * After loding this function global page show the admin panel
	 *
	 * @since    	1.0.0
	 */

	function sip_rswc_settings_page_ui() { ?>

	<div class="sip-tab-content">
		  <?php screen_icon(); ?>
		  <h2>Custom Color Settings</h2>
		  <form id="wp-color-picker-options" action="options.php" method="post">
		    <?php color_input(); ?>
		    <?php settings_fields( 'wp_color_picker_options' ); ?>
		    <?php do_settings_sections( 'wp-color-picker-settings' ); ?>

		    <p class="submit">
		      <input id="wp-color-picker-submit" name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Color' ); ?>" />
		    </p>

		  </form>
		</div>
		
		<!-- affiliate/credit link -->
		<?php include( SIP_RSWC_DIR . 'admin/ui/affiliate.php'); ?>
	<?php

	}

	/**
	 * Register settings, add a settings section, and add our color fields.
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_settings_init(){

	  register_setting(
	    'wp_color_picker_options',
	    'color_options',
	    'validate_options'
	  );
	}

	/**
	 * Display our color field as a text input field.
	 *
	 * @since    	1.0.0
	 */
	function color_input(){
	  $options 									= get_option( 'color_options' );
	  $star_color 							= ( $options['star_color'] != "" ) ? sanitize_text_field( $options['star_color'] ) : '';
	  $bar_color 								= ( $options['bar_color'] != "" ) ? sanitize_text_field( $options['bar_color'] ) : '#AD74A2';
	  $review_body_text_color 	= ( $options['review_body_text_color'] != "" ) ? sanitize_text_field( $options['review_body_text_color'] ) : '';
	  $review_background_color 	= ( $options['review_background_color'] != "" ) ? sanitize_text_field( $options['review_background_color'] ) : '';
	  $review_title_color 			= ( $options['review_title_color'] != "" ) ? sanitize_text_field( $options['review_title_color'] ) : '';
	  $load_more_button 				= ( $options['load_more_button'] != "" ) ? sanitize_text_field( $options['load_more_button'] ) : '';
	  $load_more_text 					= ( $options['load_more_text'] != "" ) ? sanitize_text_field( $options['load_more_text'] ) : '';

	 ?>
	<table>
		<tr>
			<td width="250"><strong>Review stars</strong></td>
			<td>
				<input id="star-color" name="color_options[star_color]" type="text" value="<?php echo $star_color ?>" />
	  		<div id="star-colorpicker"></div>
	  	</td>
		</tr>
		<tr>
			<td><strong>Reviews bar summary</strong></td>
			<td>
				<input id="bar-color" name="color_options[bar_color]" type="text" value="<?php echo $bar_color ?>" />
	  		<div id="bar-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Review background</strong></td>
			<td>
				<input id="review-background-color" name="color_options[review_background_color]" type="text" value="<?php echo $review_background_color ?>" />
	  		<div id="review-background-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Review body text</strong></td>
			<td>
				<input id="review-body-text-color" name="color_options[review_body_text_color]" type="text" value="<?php echo $review_body_text_color ?>" />
	  		<div id="review-body-text-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Review title</strong></td>
			<td>
				<input id="review-title-color" name="color_options[review_title_color]" type="text" value="<?php echo $review_title_color ?>" />
	  		<div id="review-title-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Load more button background</strong></td>
			<td>
				<input id="load-more-button-color" name="color_options[load_more_button]" type="text" value="<?php echo $load_more_button ?>" />
	  		<div id="load-more-button-colorpicker"></div>
			</td>
		</tr>

		<tr>
			<td><strong>Load more button text</strong></td>
			<td>
				<input id="load-more-button-text-color" name="color_options[load_more_text]" type="text" value="<?php echo $load_more_text ?>" />
	  		<div id="load-more-button-text-colorpicker"></div>
			</td>
		</tr>
	</table>
	 <?php
	}

	/**
	 * Validate the field.
	 *
	 * @since    	1.0.0
	 */
	function validate_options( $input ){
	  $valid 														= array();
	  $valid['star_color'] 							= sanitize_text_field( $input['star_color'] );
	  $valid['bar_color'] 							= sanitize_text_field( $input['bar_color'] );
	  $valid['review_body_text_color'] 	= sanitize_text_field( $input['review_body_text_color'] );
	  $valid['review_background_color'] = sanitize_text_field( $input['review_background_color'] );
	  $valid['review_title_color'] 			= sanitize_text_field( $input['review_title_color'] );
	  $valid['load_more_button'] 				= sanitize_text_field( $input['load_more_button'] );
	  $valid['load_more_text'] 					= sanitize_text_field( $input['load_more_text'] );

	  return $valid;
	}

	/**
	 * Add the script file.
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_add_styles_scripts(){
	  //Access the global $wp_version variable to see which version of WordPress is installed.
	  global $wp_version;

	  //If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.
	  if ( 3.5 <= $wp_version ){
	    //Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
	    wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script( 'wp-color-picker' );
	  }
	  //If the WordPress version is less than 3.5 load the older farbtasic color picker.
	  else {
	    //As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
	    wp_enqueue_style( 'farbtastic' );
	    wp_enqueue_script( 'farbtastic' );
	  }

	  //Load our custom Javascript file
	  wp_enqueue_script( 'wp-color-picker-settings', SIP_RSWC_URL . 'public/assets/lib/js/settings.js' );
	}
