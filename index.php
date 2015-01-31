<?php
	require( "resources/redditData.php" );
	require( "resources/redditAnalysis.php" );
?>

<html>
<head>
	<title>Karmeter</title>
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>
	<div id="intro" class="colorA">
		<h1>Karmeter</h1>
		<i>Check the moral alignment of a reddit poster based on their post content.</i>
	</div>
	<div id="input">
		<form>
			Reddit username<br>
			<input type="text" name="username" /><input type="submit" name="submit">
		</form>
		
		<?php
			$connection = new mysqli( "localhost", "root", "", "karmeter" ); // Connect to SQL
			if( $connection->connect_errno ) { // If we couldn't connect, throw an error
				echo( "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error );
			} else {
				initMySQL( $connection );
				echo( getUserScore( $connection, "pooscifer" ) );
				$connection->close();
			}
		?>
		
	</div>
	<div id="footer" class="colorGray">
		Created at UofTHacks 2015 with care by <a href="http://luaforfood.com" target="_blank">Kyle Windsor</a> and <a href="https://github.com/james2allen" target="_blank">James Allen</a>.
	</div>
</body>
</html>