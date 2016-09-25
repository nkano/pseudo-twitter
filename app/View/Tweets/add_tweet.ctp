<?php
if( $tweet ) {
	echo $this->element('putTweet', array('tweet' => $tweet));
} else {
	echo '投稿に失敗しました';	//todo: 出て消える
}
?>