<?php
	require( "resources/config.php" );
	require( "resources/redditData.php" );
	require( "resources/redditAnalysis.php" );
?>

<html>
<head>
	<title>Karmeter</title>
	<link rel="stylesheet" href="css/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.11/angular.min.js"></script>
	<script src="js/smartinput.js"></script>
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
		
		<?php
			if( isset( $_GET["username"] ) ) {
				$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
				if( $connection->connect_errno ) { // If we couldn't connect, throw an error
					echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
				} else {
					initMySQL( $connection );
					$score = getUserScore( $connection, $_GET["username"] );
					
					if( $score == -2 ) {
						echo( $_GET["username"] . " does not exist!" );
					} else {
						echo( $_GET["username"] . " has a score of " . $score );
					}
					
					$connection->close();
				}
			}
		?>
		
	</div>
	<div id="footer" class="colorGray">
		Created at UofTHacks 2015 with care by <a href="http://luaforfood.com" target="_blank">Kyle Windsor</a> and <a href="https://github.com/james2allen" target="_blank">James Allen</a>.
	</div>
</body>
</html>