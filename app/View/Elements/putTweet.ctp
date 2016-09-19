<div class=tweet>
	<div class=delete_tweet>
		<?php
		//ツイート消すボタン
		if( $tweet["Tweet"]["user_id"] == $user["id"] ) {
				echo $this->Form->postLink("消す", 
				array('action'=>"delete_tweet",$tweet["Tweet"]["id"]),
				array('class'=>'link-style'),
				'本当に削除しますか?');
		}
		?>
	</div>
	<p>
	<?php 
	//ツイート表示
	$uName = h($tweet["User"]["username"]);
	echo $this->Html->link($uName, "/tweets/posts/" . $uName );
	echo " " . h($tweet["Tweet"]["content"]);
	?>
	</p>
	
	<small><?php print( h($tweet["Tweet"]["time"])); ?></small>
</div>