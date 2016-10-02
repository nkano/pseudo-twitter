<?php echo $this->Html->script( 'jquery-3.1.0.min.js', array( 'inline' => false)); ?>
<?php echo $this->Html->script( 'hoverColor.js', array( 'inline' => false)); ?>


<?php
//デバッグ
//echo "<pre>"; print_r($latest_tweets); echo "</pre>";
?>

<?php
	echo $this->element( 'putUserStatus' );
?>

<h1>
<?php
echo $this->Html->link(h($username), '/tweets/posts/' . $username);
echo 'さんのフォロー';
?>
</h1>

<!- //フォローしてる人一覧を表示->

<?php
foreach($users as $user):
		echo $this->element('putUserSimple', array('user' => $user));
endforeach; ?>
</div>

