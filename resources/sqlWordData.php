<?php
	function initMySQL( $connection ) { // Create the Words table in the SQL DB if it doesn't exist yet
		$ret = $connection->query( "CREATE TABLE IF NOT EXISTS Words( Word VARCHAR(100), Good INT, Bad INT, PRIMARY KEY ( Word ) )" );
		if( !$ret ) {
			echo( "Failed to create MySQL table: " . $connection->error );
		}
	}
	
	function getWordScores( $connection, $comment ) { // Get the score of a certain comment (returns an array of array( Good, Bad ))
		$sanitizedComment = $connection->escape_string( $comment );
		$redundantArray = explode( " ", $sanitizedComment );
		$arr = array_keys( array_flip( $redundantArray ) );
		$wordQuery = "";
		for( $i = 0; $i < count( $arr ); $i++ ) {
			$wordQuery = $wordQuery . "'" . $arr[$i] . "'";
			if( $i < count( $arr ) - 1 ) {
				$wordQuery = $wordQuery . ",";
			}
		}
		$countedValues = array_count_values( $redundantArray );
		
		$res = $connection->query( "SELECT Word, Good, Bad FROM Words WHERE Word IN (" . $wordQuery . " );" ); // Get the scores from the database
		
		if( $res ) { // There are words in the database
			$ret = array();
			$i = 0;
			while( $row = $res->fetch_array() ) {
				$ret[$i] = array( "Good" => $row['Good'] * $countedValues[$row['Word']], "Bad" => $row['Bad'] * $countedValues[$row['Word']], "Word" => $row['Word'] );
				$i++;
			}
			return $ret;
		} else {
			echo( "Failed to get word scores: " . $connection->error );
		}
		return array( "Good" => 0, "Bad" => 0 ); // Return nothing
	}
	
	function incrementGoodComment( $comment ) {
		$redundantArray = explode( " ", $comment );
		$arr = array_keys( array_flip( $redundantArray ) );
		$countedValues = array_count_values( $redundantArray );
		for( $i = 0; $i < count( $arr ); $i++ ) {
			for( $n = 0; $n < $countedValues[$arr[$i]]; $n++ ) {
				incrementGood( $arr[$i] );
			}
		}
	}
	
	function incrementBadComment( $comment ) {
		$redundantArray = explode( " ", $comment );
		$arr = array_keys( array_flip( $redundantArray ) );
		$countedValues = array_count_values( $redundantArray );
		
		for( $i = 0; $i < count( $arr ); $i++ ) {
			for( $n = 0; $n < $countedValues[$arr[$i]]; $n++ ) {
				incrementBad( $arr[$i] );
			}
		}
	}
	
	function incrementComment( $comment, $score ) {
		if( $score == 0 ) {
			return;
		}
		
		$redundantArray = explode( " ", $comment );
		$arr = array_keys( array_flip( $redundantArray ) );
		$countedValues = array_count_values( $redundantArray );
		
		for( $i = 0; $i < count( $arr ); $i++ ) {
			for( $n = 0; $n < $countedValues[$arr[$i]]; $n++ ) {
				incrementWord( $arr[$i], $score );
			}
		}
	}
	
	function incrementGood( $word ) {
		if( empty( $word ) ) {
			return;
		}
		if( strlen( $word ) <= 3 ) {
			return;
		}
		
		$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
		if( $connection->connect_errno ) { // If we couldn't connect, throw an error
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection );
			$res = $connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( '" . $connection->real_escape_string( $word ) . "', 1, 0 ) ON DUPLICATE KEY UPDATE Good = Good + 1" );
			if( !$res ) {
				echo( "Failed to increment 'good' word: " . $connection->error . "<br />" );
			}
		}
		$connection->close();
	}
	
	function incrementBad( $word ) {
		if( empty( $word ) ) {
			return;
		}
		if( strlen( $word ) <= 3 ) {
			return;
		}
		
		$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
		if( $connection->connect_errno ) { // If we couldn't connect, throw an error
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection );
			$res = $connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( '" . $connection->real_escape_string( $word ) . "', 0, 1 ) ON DUPLICATE KEY UPDATE Bad = Bad + 1" );
			if( !$res ) {
				echo( "Failed to increment 'bad' word: " . $connection->error . "<br />" );
			}
		}
		$connection->close();
	}
	
	function incrementWord( $word, $score ) {
		if( empty( $word ) ) {
			return;
		}
		if( strlen( $word ) <= 3 ) {
			return;
		}
		if( in_array( $word, BLACKLIST_WORDS ) ) {
			return;
		}
		
		$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
		if( $connection->connect_errno ) { // If we couldn't connect, throw an error
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection );
			if( $score < 0 ) {
				$res = $connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( '" . $connection->real_escape_string( $word ) . "', 0, " . ( $score * -1 ) . " ) ON DUPLICATE KEY UPDATE Bad = Bad + " . ( $score * -1 ) );
			} else {
				$res = $connection->query( "INSERT INTO Words ( Word, Good, Bad ) VALUES ( '" . $connection->real_escape_string( $word ) . "', " . $score . ", 0 ) ON DUPLICATE KEY UPDATE Good = Good + " . $score );
			}
			if( !$res ) {
				echo( "Failed to change word: " . $connection->error . "<br />" );
			}
		}
		$connection->close();
	}
?>