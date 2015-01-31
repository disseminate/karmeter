<?php
	function getUserComments( $username ) {
		$JSON = json_decode( file_get_contents( "https://www.reddit.com/user/" . $username . ".json" ) );
		
		if( isset( $JSON->error ) ) {
			// Todo: Handle error
		} else {
			$releventJSON = (array)$JSON->data->children;
			$ret = array();
			
			for( $i = 0; $i < count( $releventJSON ); $i++ ) {
				if( isset( $releventJSON[$i]->data->body ) ) {
					$ret[$i] = $releventJSON[$i]->data->body;
				}
			}
			
			return $ret;
		}
		
		return array();
	}
?>