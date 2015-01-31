<?php
	const BAYES_PROBABILITY_BAD = 0.5; // Probability that any given post on reddit is "bad"
	const KEYWORD_THRESHOLD = 0.5; // The ratio of bad-to-good or good-to-bad score must be at least this for a keyword to affect the overall badness score. In other words, if we have a word with Bad = 2, Good = 1, it will be displayed. If it's 5 Bad and 6 Good, it won't.
	const MIN_KEYWORD = 1; // Minimum Good or Bad entries for a keyword to be used in analysis
?>