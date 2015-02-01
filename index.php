<!DOCTYPE html>
<?php
	require( "resources/config.php" );
	require( "resources/redditData.php" );
	require( "resources/redditAnalysis.php" );
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Karmeter</title>
	<link rel="stylesheet" href="css/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.11/angular.min.js"></script>
	<script src="js/smartinput.js"></script>
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
	<div id="intro" class="colorA">
		<h1>Karmeter</h1>
		<i>Check the moral alignment of a Reddit user based on their comments.</i>
	</div>
	<div id="input">
		<form>
			<input type="text" name="username" class="big clearableText" value="Reddit Username" /><input type="submit" value="Judge" class="big" />
		</form>
	</div>
	<div id="bar">
	<?php
		if( isset( $_GET["username"] ) ) {
			$connection = new mysqli( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB ); // Connect to SQL
			if( $connection->connect_errno ) { // If we couldn't connect, throw an error
				echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
			} else {
				initMySQL( $connection );
				$score = getUserScore( $connection, $_GET["username"] );
				
				$fakeScore = ( $score + 1 ) / 2;
				echo( '<div id="slider" style="width:' . ( $fakeScore * 90 ) . '%"> </div></div>' );
				
				echo( '<div id="analysis">' );
				if( $score == -2 ) {
					echo( "<b>" . $_GET["username"] . "</b> does not exist!</div>" );
				} else {
					$type = "positive";
					if( $score < 0 ) {
						$type = "negative";
					}
					echo( "<b><a href='http://reddit.com/u/" . $_GET["username"] . "' target='_blank'>" . $_GET["username"] . "</a></b> has a score of <b>" . round( ( $score + 1 ) * 5, 2 ) . "/10</b>.</div>" );
					echo( "<div id='analysisSub'>This is based on the words used in the comment history of the user.<br />This user makes mostly " . $type . " comments." );
				}
				
				$connection->close();
			}
		} else {
			echo( '</div>' );
		}
	?>
	<div id="footerPad"> </div>
	<div id="footer" class="colorGray">
		Created at UofTHacks 2015 with &#9825; by <a href="http://luaforfood.com" target="_blank">Kyle Windsor</a> and <a href="https://github.com/james2allen" target="_blank">James Allen</a>. Open source on <a href="https://github.com/disseminate/karmeter" target="_blank">GitHub</a>.
	</div>
</body>
</html>