// Hiding the panel content. If JS is inactive, content will be displayed
  jQuery( '.panel-content' ).hide();

  // Preparing the DOM
  
  // -- Update the markup of accordion container 
  jQuery( '.accordion' ).attr({
    role: 'tablist',
    multiselectable: 'true'
   });

  // -- Adding ID, aria-labelled-by, role and aria-labelledby attributes to panel content
  jQuery( '.panel-content' ).attr( 'id', function( IDcount ) { 
    return 'panel-' + IDcount; 
  });
  jQuery( '.panel-content' ).attr( 'aria-labelledby', function( IDcount ) { 
    return 'control-panel-' + IDcount; 
  });
  jQuery( '.panel-content' ).attr( 'aria-hidden' , 'true' );
  // ---- Only for accordion, add role tabpanel
  jQuery( '.accordion .panel-content' ).attr( 'role' , 'tabpanel' );
  
  // -- Wrapping panel title content with a <a href="">
  jQuery( '.panel-title' ).each(function(i){
    
    // ---- Need to identify the target, easy it's the immediate brother
    $target = jQuery(this).next( '.panel-content' )[0].id;
    
    // ---- Creating the link with aria and link it to the panel content
    $link = $( '<a>', {
      'href': '#' + $target,
      'aria-expanded': 'false',
      'aria-controls': $target,
      'id' : 'control-' + $target
    });
    
    // ---- Output the link
    jQuery(this).wrapInner($link);  
    
  });

  // Optional : include an icon. Better in JS because without JS it have non-sense.
  jQuery( '.panel-title a' ).append('<span class="icon"><b>+<b></span>');

  // Now we can play with it
  jQuery( '.panel-title a' ).click(function() {
    
    if (jQuery(this).attr( 'aria-expanded' ) == 'false'){ //If aria expanded is false then it's not opened and we want it opened !
      
      // -- Only for accordion effect (2 options) : comment or uncomment the one you want
      
      // ---- Option 1 : close only opened panel in the same accordion
      //      search through the current Accordion container for opened panel and close it, remove class and change aria expanded value
      jQuery(this).parents( '.accordion' ).find( '[aria-expanded=true]' ).attr( 'aria-expanded' , false ).removeClass( 'active' ).parent().next( '.panel-content' ).slideUp(200).attr( 'aria-hidden' , 'true');

      // Option 2 : close all opened panels in all accordion container
      //$('.accordion .panel-title > a').attr('aria-expanded', false).removeClass('active').parent().next('.panel-content').slideUp(200);
      
      // Finally we open the panel, set class active for styling purpos on a and aria-expanded to "true"
      jQuery(this).attr( 'aria-expanded' , true ).addClass( 'active' ).parent().next( '.panel-content' ).slideDown(200).attr( 'aria-hidden' , 'false');

    } else { // The current panel is opened and we want to close it

      jQuery(this).attr( 'aria-expanded' , false ).removeClass( 'active' ).parent().next( '.panel-content' ).slideUp(200).attr( 'aria-hidden' , 'true');;

    }
    // No Boing Boing
    return false;
  });