<?php
	const MYSQL_HOST = "localhost";
	const MYSQL_USER = "root";
	const MYSQL_PASS = "";
	const MYSQL_DB = "karmeter";
	
	const BAYES_PROBABILITY_BAD = 0.4; // Probability that any given post on reddit is "bad"
	const KEYWORD_THRESHOLD = 0.5; // The ratio of bad-to-good or good-to-bad score must be at least this for a keyword to affect the overall badness score. In other words, if we have a word with Bad = 2, Good = 1, it will be displayed. If it's 5 Bad and 6 Good, it won't.
	const MIN_WORD_LEN = 4; // Minimum length of a word to be suitable as a keyword.
	const MIN_KEYWORD = 0; // Minimum Good or Bad entries for a keyword to be used in analysis
	const SCALE_EXPONENT = 0.3; // Take the user score to this exponent before returning it. Used to make values more/less neutral.
	
	const REDDIT_NUM_RECORDS = 100; // Take only the N most recent comments on reddit.
	
	const BLACKLIST_WORDS = array(
		0 => "this",
		1 => "that",
		2 => "they",
		3 => "some",
		4 => "those",
		5 => "would",
		6 => "when",
		7 => "your",
		8 => "these",
		9 => "just",
		10 => "have",
		11 => "like",
		12 => "with",
		13 => "will",
		14 => "what",
		15 => "then",
		16 => "them",
		17 => "much",
		18 => "very"
	); // Words that should be ignored as keywords.
?>