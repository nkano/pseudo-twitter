<?php
	echo $this->Html->script( 'jquery-3.1.0.min.js', array( 'inline' => false));
	echo $this->Html->script( 'loadTweetsWhenScrolled.js', array( 'inline' => false));
	echo $this->Html->script( 'addTweet.js', array( 'inline' => false));
	echo $this->Html->script( 'loadTweetsPeriodically.js', array( 'inline' => false));
	echo $this->Html->script( 'hoverColor.js', array( 'inline' => false));
 ?>


<?php
	echo $this->element('putUserStatus');
?>

<h1>ホーム</h1>

<small>いまどうしてる？</small>
<strong><?php echo $this->Session->flash(); ?></strong>
<?php print(
  $this->Form->create('Tweet', array('default'=>false)) .
  $this->Form->input('content', array( 'label' => false ) ) .
  $this->Form->end('つぶやく')
); ?>

<small id=latest_tweet>
<?php
if( !empty($latest_tweet) ) {
	echo "最新のつぶやき: ". $latest_tweet["Tweet"]["content"] . " ";
	echo $latest_tweet["Tweet"]["time"];
} else {
	echo "呟いてみましょう！";	//ツイート数0のとき
}
?>
</small>


<div id="tweetlist" data-page_num=1 data-current_location="index">
	<?php
		foreach($tweets as $tweet):
			echo $this->element('putTweet', array('tweet' => $tweet));
		endforeach;
	?>
</div>

