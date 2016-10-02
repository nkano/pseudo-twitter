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
foreach($follows as $follow):
	$current_id = $follow["Follow"]["follow_id"];
?>
	<div class=userSimple>
		<div class=follow>
		<?php
			//フォローボタン
			if( $user_id == $authUser["id"]
			and !empty( $authUser )
			and $authUser["id"] != $current_id ) {
				//アンフォローボタンを表示する
				echo $this->Form->postButton("unfollow", "/Users/delete_follow/",
						array('data'=> array('follow_id'=>$current_id)));
				}
		?>
		</div>
		
		<p>
			<?php
			// ユーザー名
			$followerName = $follow["Follower"]["username"];
			echo $this->Html->link(h($followerName), '/tweets/posts/' . $followerName );
			?>
		</p>
		<small>
			<?php
			//直近のツイートを表示
			if( !empty($latest_tweets[$current_id]) ) {
				echo $latest_tweets[$current_id]["Tweet"]["content"];
				echo ' '. $latest_tweets[$current_id]["Tweet"]["time"];
			} else {
				echo "";	//ツイート数0のとき
			}
			?>
		</small>
	</div>
<?php endforeach; ?>
</div>


<div class=paging>
	<?php
	echo $this->Paginator->prev('< 前へ', array(), null, array('class' => 'prev disabled'));
	//echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next('次へ >', array(), null, array('class' => 'next disabled'));
	?>
</div>