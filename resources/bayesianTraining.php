<?php
	require( "redditData.php" );
	require( "redditAnalysis.php" );
?>

<html>
<head>
	<title>Karmeter Training</title>
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
	<div id="training">
		<?php
			if( isset( $_POST['good'] ) ) {
				incrementGoodComment( $_POST['text'] );
			} elseif( isset( $_POST['bad'] ) ) {
				incrementBadComment( $_POST['text'] );
			}
			
			$comment = getRandomComment();
			$cleanComment = preg_replace( '/[^a-z]+/i', ' ', strtolower( $comment ) );
			echo( '<form method="post" action="bayesianTraining.php">' );
				echo( "<blockquote>" );
				echo( $cleanComment );
				echo( "</blockquote><p />" );
				echo( "<input type='hidden' name='text' value='" . $cleanComment . "'>" );
				echo( "How does this post sound?" );
				echo( '<input type="submit" value="Good" name="good">' );
				echo( '<input type="submit" value="Bad" name="bad">' );
				echo( '<input type="submit" value="Neutral" name="neutral">' );
			echo( "</form>" );
		?>
	</div>
</body>
</html>