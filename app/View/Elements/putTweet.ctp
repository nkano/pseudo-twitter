<div class=tweet>
	<div class=delete_tweet>
		<?php
		//ツイート消すボタン
		if( $tweet["Tweet"]["user_id"] == $authUser["id"] ) {
				echo $this->Form->postLink("消す", 
				array('action'=>"delete_tweet",$tweet["Tweet"]["id"]),
				array('class'=>'link-style'),
				'本当に削除しますか?');
		}
		?>
	</div>
	<p>
	<span class=tweet_username>
	<?php 
	//名前表示
	$uName = h($tweet["User"]["username"]);
	echo $this->Html->link($uName, "/tweets/posts/" . $uName ) . " ";
	?>
	</span>
	
	<span class=tweet_content>
	<?php
	//ツイート表示
	echo h($tweet["Tweet"]["content"]);
	?>
	</span>
	</p>
	
	<small class=tweet_date><?php print( h($tweet["Tweet"]["time"])); ?></small>
</div>