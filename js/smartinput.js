$( document ).ready( function() {
	var defaultValue = $( ".clearableText" ).val(); // Get the original value (so we don't replace user input)
	$( ".clearableText" ).focus( function() { // On selected
		if( this.value == defaultValue ) { // If the input has the default value
			$( this ).val( "" ); // Remove it and the "gray text" effect
			$( this ).removeClass( "clearableText" );
		}
	} );
	
	if( $( "#slider" ).length > 0 ) {
		var w = $( "#slider" ).width() / $( "#slider" ).parent().width(); // Animate the bar
		$( "#slider" ).width( 0 );
		$( "#slider" ).animate( { width: ( w * 100 ) + '%' }, 1000 );
	}
} );
