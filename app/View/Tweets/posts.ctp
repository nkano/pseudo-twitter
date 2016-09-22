<?php echo $this->Html->script( 'jquery-3.1.0.min.js', array( 'inline' => false)); ?>
<?php echo $this->Html->script( 'hoverColor.js', array( 'inline' => false)); ?>
<?php echo $this->Html->script( 'loadTweetsWhenScrolled.js', array( 'inline' => false)); ?>

<?php
	echo $this->element('putUserStatus' )
?>

<h1><?php print(h($name)); ?>さんの投稿一覧</h1>
<small id=latest_tweet>
<?php
if( !empty($latest_tweet) ) {
	echo "最新のつぶやき: ". $latest_tweet["Tweet"]["content"] . " ";
	echo $latest_tweet["Tweet"]["time"];
} else {
	echo "";//ツイート数0のとき
}
//（この画面には無くてもいい気がする）
?>
</small>

<?php echo '<div id="tweetlist" data-page_num=1 data-current_location="posts/'. $name. '">' ?>
	<?php
		foreach($tweets as $tweet):
			echo $this->element('putTweet', array('tweet' => $tweet));
		endforeach;
	?>
</div>
