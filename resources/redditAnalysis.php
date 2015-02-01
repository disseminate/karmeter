<?php
	require( "sqlWordData.php" );
	
	function getUserScore( $connection, $username ) {
		$comments = getUserComments( $username ); // Get a user's comments
		
		if( $comments == -1 ) {
			return -2;
		}
		
		if( count( $comments ) == 0 ) { // No comments? No score
			return 0;
		}
		
		$n = 0;
		$total = 0;
		while( $n < count( $comments ) ) { // Get the bad probability of all comments & average them
			$total += badCommentProbability( $connection, $comments[$n] );
			$n++;
		}
		
		$ret = ( 1 - $total / $n ) * 2 - 1;
		echo( $ret . " - " );
		if( $ret < 0 ) {
			$ret = pow( abs( $ret ), SCALE_EXPONENT ) * -1;
		} else {
			$ret = abs( pow( $ret, SCALE_EXPONENT ) );
		}
		echo( $ret );
		return $ret;
	}
	
	function badCommentProbability( $connection, $comment ) {
		$sanitizedComment = preg_replace( '/[^a-z]+/i', ' ', strtolower( $comment ) ); // Remove punctuation
		$score = getWordScores( $connection, $sanitizedComment ); // Get the scores of every word
		$n = 0;
		$sumGoodProb = 0; // We are going to average the individual word scores to get an overall comment score
		$sumBadProb = 0;
		while( $n < count( $score ) ) {
			$ratioGoodBad = 2;
			if( $score[$n]['Bad'] > 0 ) {
				$ratioGoodBad = $score[$n]['Good'] / $score[$n]['Bad'];
			}
			if( abs( $ratioGoodBad - 1 ) >= KEYWORD_THRESHOLD && $score[$n]['Good'] >= MIN_KEYWORD && $score[$n]['Bad'] >= MIN_KEYWORD ) {
				$total =  $score[$n]['Good'] + $score[$n]['Bad'];
				if( $total > 0 ) {
					$sumGoodProb += $score[$n]['Good'] / $total; // Increase the good and bad probabilities (to average later). We only use probabilities in Bayesian processes, so no need to retain scores
					$sumBadProb += $score[$n]['Bad'] / $total;
				}
				//echo( $score[$n]['Word'] . " - G" . $score[$n]['Good'] . ", B" . $score[$n]['Bad'] . "<br>" );
			}
			$n++;
		}
		
		if( $n == 0 ) {
			return BAYES_PROBABILITY_BAD;
		}
		
		$goodProb = $sumGoodProb / $n; // Average them
		$badProb = $sumBadProb / $n;
		
		if( $goodProb == 0 && $badProb == 0 ) { // If there's no data (completely unique comment not in database)
			return BAYES_PROBABILITY_BAD;
		}
		
		$probMessageBad = $badProb * BAYES_PROBABILITY_BAD / ( $badProb * BAYES_PROBABILITY_BAD + $goodProb * ( 1 - BAYES_PROBABILITY_BAD ) ); // Bayes' Theorem
		
		return $probMessageBad; // Return the probability
	}
?>