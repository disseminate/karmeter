<?php
	function initMySQL( $connection ) { // Create the Words table in the SQL DB if it doesn't exist yet
		$ret = $connection->query( "CREATE TABLE IF NOT EXISTS Words( id INT NOT NULL auto_increment, Word VARCHAR(100), Good INT, Bad INT, PRIMARY KEY ( id ) )" );
		if( !$ret ) {
			echo( "Failed to create MySQL table: " . $connection->error );
		}
	}
	
	function getWordScore( $connection, $word ) { // Get the score of a certain word (returns Array( Good, Bad ))
		$res = $connection->query( "SELECT Good, Bad FROM Words WHERE Word=" . $connection->escape_string( $word ) . ";" ); // Get the scores from the database
		
		if( !$res ) { // The word isn't in the database yet
			$ret = $connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( '" . $connection->real_escape_string( $word ) . "', 0, 0 )" ); // Insert it
			if( !$ret ) {
				echo( "Failed to insert word '" . $connection->real_escape_string( $word ) . "': " . $connection->error );
			}
		} else { // Word's in the database
			$data = $res->fetch_assoc(); // Return its information
			return array( "Good" => $data['Good'], "Bad" => $data['Bad'] );
		}
		return array( "Good" => 0, "Bad" => 0 ); // Return nothing
	}
	
	function incrementGood( $connection, $word ) {
		$res = $connection->query( "SELECT Good, Bad FROM Words WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
		
		if( !$res ) { // Unlike above, we insert with a starting "Good" score
			$connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( " . $connection->real_escape_string( $word ) . ", 1, 0 )" );
		} else { // If it already exists, just increment it
			$connection->query( "UPDATE Words SET Good = Good + 1 WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
		}
	}
	
	function incrementBad( $connection, $word ) {
		$res = $connection->query( "SELECT Good, Bad FROM Words WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
		
		if( !$res ) {
			$connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( " . $connection->real_escape_string( $word ) . ", 0, 1 )" );
		} else {
			$connection->query( "UPDATE Words SET Bad = Bad + 1 WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
		}
	}
?>