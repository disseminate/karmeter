<?php
	function getUserComments( $username ) {
		$url = "https://www.reddit.com/user/" . $username . "/comments.json?limit=1000";
		$header = @get_headers( $url );
		if($header[0] == 'HTTP/1.1 404 Not Found') {
			return -1;
		}
		
		$JSON = json_decode( file_get_contents( $url ) ); // Get the Reddit JSON
		
		if( isset( $JSON->error ) ) {
			// Todo: Handle error
		} else {
			$releventJSON = $JSON->data->children; // Get the actual data from the JSON
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
		
		return -1;
	}
	
	function getRandomComment() {
		$file = file_get_contents( "http://www.reddit.com/r/random/comments.json?limit=1" );
		while( !$file ) {
			$file = file_get_contents( "http://www.reddit.com/r/random/comments.json?limit=1" );
		}
		$JSON = json_decode( $file );
		if( count( $JSON->data->children ) == 0 ) {
			return "";
		}
		return $JSON->data->children[0]->data->body;
	}
?>