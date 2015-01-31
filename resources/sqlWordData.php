<?php
	function initMySQL( $connection ) { // Create the Words table in the SQL DB if it doesn't exist yet
		$ret = $connection->query( "CREATE TABLE IF NOT EXISTS Words( id INT NOT NULL auto_increment, Word VARCHAR(100), Good INT, Bad INT, PRIMARY KEY ( id ) )" );
		if( !$ret ) {
			echo( "Failed to create MySQL table: " . $connection->error );
		}
	}
	
	function getWordScores( $connection, $comment ) { // Get the score of a certain comment (returns an array of array( Good, Bad ))
		$sanitizedComment = $connection->escape_string( $comment );
		$redundantArray = explode( " ", $sanitizedComment );
		$arr = array_keys( array_flip( $redundantArray ) );
		$wordQuery = "'";
		for( $i = 0; $i < count( $arr ); $i++ ) {
			$wordQuery = $wordQuery . $arr[$i] . "'";
			if( $i < count( $arr ) - 1 ) {
				$wordQuery = $wordQuery . ",'";
			}
		}
		$countedValues = array_count_values( $redundantArray );
		
		$res = $connection->query( "SELECT Word, Good, Bad FROM Words WHERE Word IN (" . $wordQuery . " );" ); // Get the scores from the database
		
		if( $res ) { // There are words in the database
			$ret = array();
			$i = 0;
			while( $row = $res->fetch_array() ) {
				$ret[$i] = array( "Good" => $row['Good'] * $countedValues[$row['Word']], "Bad" => $row['Bad'] * $countedValues[$row['Word']] );
				$i++;
			}
			return $ret;
		} else {
			echo( "Failed to get word scores: " . $connection->error );
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