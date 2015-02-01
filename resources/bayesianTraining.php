<?php
	require( "config.php" );
	require( "redditData.php" );
	require( "redditAnalysis.php" );
?>

<html>
<head>
	<title>Karmeter Training</title>
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
	<?php
		if( isset( $_POST['score'] ) ) {
			incrementComment( $_POST['text'], intval( $_POST['score'] ) );
		}
		
		$comment = getRandomComment();
		$cleanComment = preg_replace( '/[^a-z]+/i', ' ', strtolower( $comment ) );
		echo( '<form method="post" action="bayesianTraining.php">' );
			echo( "<blockquote>" );
			echo( $cleanComment );
			echo( "</blockquote><p />" );
			echo( "<input type='hidden' name='text' value='" . $cleanComment . "'>" );
			for( $i = -2; $i <= 2; $i++ ) {
				echo( '<input type="submit" value="' . $i . '" name="score">' );
			}
		echo( "</form>" );
		
		$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
		if( $connection->connect_errno ) { // If we couldn't connect, throw an error
			echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
		} else {
			initMySQL( $connection );
			$p = badCommentProbability( $connection, $cleanComment );
			echo( "<p />Based on the data we already have, this has a <b>" . round( $p * 100, 2 ) . "%</b> chance of being a bad post." );
		}
		$connection->close();
	?>
</body>
</html>