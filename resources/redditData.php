<?php
	function getUserComments( $username ) {
		$JSON = json_decode( file_get_contents( "https://www.reddit.com/user/" . $username . ".json" ) ); // Get the Reddit JSON
		
		if( isset( $JSON->error ) ) {
			// Todo: Handle error
		} else {
			$releventJSON = (array)$JSON->data->children; // Get the actual data from the JSON
			$ret = array(); // Initialize return array
			
			$c = 0;
			for( $i = 0; $i < count( $releventJSON ); $i++ ) {
				if( isset( $releventJSON[$i]->data->body ) ) { // If the reddit data is from a comment, not a link/textpost
					$ret[$c] = $releventJSON[$i]->data->body; // Add it to the return array
					$c++;
				}
			}
			
			return $ret;
		}
		
		return array();
	}
?>