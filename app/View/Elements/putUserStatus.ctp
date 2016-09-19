<div class=user>
<p>
<?php
echo $this->Html->link(h($username),
		'/tweets/posts/' . h($username)) . "さん";
?>
</p>
<?php
echo "\nフォロー: ". $this->Html->link($following_num,
		'/follows/follows/' . $username);
echo "\nフォロワー: ". $this->Html->link($followers_num,
		'/follows/followers/' . $username);
echo "\n呟いた数: ". $this->Html->link($tweets_num,
		'/tweets/posts/' . $username);
?>
</div>