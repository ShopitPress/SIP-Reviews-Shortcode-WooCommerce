//Setup the color pickers to work with our text input field
jQuery(document).ready(function(){
  "use strict";
  
  //This if statement checks if the color picker widget exists within jQuery UI
  //If it does exist then we initialize the WordPress color picker on our text input field
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#star-color' ).wpColorPicker();
  }
  else {
    //We use farbtastic if the WordPress color picker widget doesn't exist
    jQuery( '#star-colorpicker' ).farbtastic( '#star-color' );
  }

  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#bar-color' ).wpColorPicker();
  }
  else {
    jQuery( '#bar-colorpicker' ).farbtastic( '#bar-color' );
  }
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#load-more-button-color' ).wpColorPicker();
  }
  else {
    jQuery( '#load-more-button-colorpicker' ).farbtastic( '#load-more-button-color' );
  }
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#load-more-button-text-color' ).wpColorPicker();
  }
  else {
    jQuery( '#load-more-button-text-colorpicker' ).farbtastic( '#load-more-button-text-color' );
  }
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#review-background-color' ).wpColorPicker();
  }
  else {
    jQuery( '#review-background-colorpicker' ).farbtastic( '#review-background-color' );
  }
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#review-body-text-color' ).wpColorPicker();
  }
  else {
    jQuery( '#review-body-text-colorpicker' ).farbtastic( '#review-body-text-color' );
  }
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery( '#review-title-color' ).wpColorPicker();
  }
  else {
    jQuery( '#review-title-colorpicker' ).farbtastic( '#review-title-color' );
  }
});
