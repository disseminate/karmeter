<?php
	require( "sqlWordData.php" );
	
	function getUserScore( $connection, $username ) {
		$comments = getUserComments( $username ); // Get a user's comments
		
		if( count( $comments ) == 0 ) { // No comments? No score
			return 0;
		}
		
		$n = 0;
		$total = 0;
		while( $n < count( $comments ) ) { // Get the bad probability of all comments & average them
			$total += badCommentProbability( $connection, $comments[$n] );
			$n++;
		}
		
		$normalizedTotal = ( 1 - $total / $n ) * 2 - 1; // Average and remap the probability: -1 bad, 1 good
		
		return $normalizedTotal;
	}
	
	function badCommentProbability( $connection, $comment ) {
		$sanitizedComment = explode( ' ', preg_replace( '/[^a-z]+/i', ' ', strtolower( $comment ) ) ); // Remove punctuation
		$n = 0;
		$sumGoodProb = 0; // We are going to average the individual word scores to get an overall comment score
		$sumBadProb = 0;
		while( $n < count( $sanitizedComment ) ) {
			$score = getWordScore( $connection, $sanitizedComment[$n] );
			$total =  $score['Good'] + $score['Bad'];
			if( $total > 0 ) {
				$sumGoodProb += $score['Good'] / $total; // Increase the good and bad probabilities (to average later). We only use probabilities in Bayesian processes, so no need to retain scores
				$sumBadProb += $score['Bad'] / $total;
			}
			$n++;
		}
		$goodProb = $sumGoodProb / $n; // Average them
		$badProb = $sumBadProb / $n;
		
		if( $goodProb == 0 && $badProb == 0 ) { // If there's no data (completely unique comment not in database)
			return 0.5;
		}
		
		$probMessageBad = $badProb * 0.5 / ( $badProb * 0.5 + $goodProb * 0.5 ); // Bayes' Theorem
		
		return $probMessageBad; // Return the probability
	}
?>