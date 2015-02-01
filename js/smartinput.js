$( document ).ready( function() {
	var defaultValue = $( ".clearableText" ).val(); // Get the original value (so we don't replace user input)
	$( ".clearableText" ).focus( function() { // On selected
		if( this.value == defaultValue ) { // If the input has the default value
			$( this ).val( "" ); // Remove it and the "gray text" effect
			$( this ).removeClass( "clearableText" );
		}
	} );
} );