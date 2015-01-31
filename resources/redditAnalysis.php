<?php
	function getUserScore( $username ) {
		$comments = getUserComments( $username );
		return 0.75; // Test score
	}
?>