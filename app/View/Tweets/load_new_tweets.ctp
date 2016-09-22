<?php foreach($tweets as $tweet):
	echo $this->element('putTweet', array('tweet' => $tweet));
endforeach; ?>