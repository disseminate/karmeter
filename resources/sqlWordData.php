<?php
	function initMySQL( $connection ) { // Create the Words table in the SQL DB if it doesn't exist yet
		$connection->query( "CREATE TABLE IF NOT EXISTS Words( id INT NOT NULL AUTOINCREMENT PRIMARY KEY, Word VARCHAR(100), Good INT, Bad INT )" );
	}
	
	function getWordScore( $word ) { // Get the score of a certain word (returns Array( Good, Bad ))
		$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
		if( $connection->connect_errno ) { // If we couldn't connect, throw an error
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection ); // Ensure the table exists
			
			$res = $connection->query( "SELECT Good, Bad FROM Words WHERE Word=" . $connection->real_escape_string( $word ) . ";" ); // Get the scores from the database
			
			if( !$res ) { // The word isn't in the database yet
				$connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( " . $connection->real_escape_string( $word ) . ", 0, 0 )" ); // Insert it
			} else { // Word's in the database
				$data = $res->fetch_assoc(); // Return its information
				$connection->close();
				return array( "Good" => $data['Good'], "Bad" => $data['Bad'] );
			}
		}
		$connection->close();
		return array( "Good" => 0, "Bad" => 0 ); // Return nothing
	}
	
	function incrementGood( $word ) {
		$connection = new mysqli( "localhost", "root", "", "karmeter" );
		if( $connection->connect_errno ) {
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection );
			
			$res = $connection->query( "SELECT Good, Bad FROM Words WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
			
			if( !$res ) { // Unlike above, we insert with a starting "Good" score
				$connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( " . $connection->real_escape_string( $word ) . ", 1, 0 )" );
			} else { // If it already exists, just increment it
				$connection->query( "UPDATE Words SET Good = Good + 1 WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
			}
		}
	}
	
	function incrementBad( $word ) {
		$connection = new mysqli( "localhost", "root", "", "karmeter" );
		if( $connection->connect_errno ) {
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection );
			
			$res = $connection->query( "SELECT Good, Bad FROM Words WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
			
			if( !$res ) {
				$connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( " . $connection->real_escape_string( $word ) . ", 0, 1 )" );
			} else {
				$connection->query( "UPDATE Words SET Bad = Bad + 1 WHERE Word=" . $connection->real_escape_string( $word ) . ";" );
			}
		}
	}
?>