<?php echo $this->Html->script( 'jquery-3.1.0.min.js', array( 'inline' => false)); ?>
<?php echo $this->Html->script( 'hoverColor.js', array( 'inline' => false)); ?>

<?php
//デバッグ
//echo "<pre>"; print_r($result); echo "</pre>";
?>

<h1>検索結果</h1>
<?php 
	$options = array('label' => '名前', 'required' => false, 'default' => h($query) );
	print(
		$this->Form->create('User', array('type' => 'get', 'url' => 'search_result')) .
		$this->Form->input('username', $options) .
		$this->Form->end('検索')
	);
?>

<h2>
<?php
	echo $this->Session->flash();	//"検索結果は○件です"
?>
</h2>


<?php foreach( $result as $r ) { ?>
	<div class=userSimple>
		<div class=follow>
			<?php
				//フォローボタン
				if( !empty( $user ) and $user["id"] != $r["User"]["id"] ) {
					
					//フォロー済みかチェック
					$isFollow = false;
					foreach( $follows as $f ) {
						if( $f["Follow"]["follow_id"] == $r["User"]["id"] ) {
							$isFollow = true;
							break;
						}
					}
					if( $isFollow ) {
						echo "フォロー済み";
					} else {
						//未フォローならボタンを表示する
						echo $this->Form->postButton("follow", "/Users/add_follow/",
								array('data'=> array('follow_id'=>$r["User"]["id"])));
					}
				}
			?>
		</div>
		<p>
			<?php
			//名前
			echo $this->Html->link(h($r["User"]["username"]), "/tweets/posts/"
				. h($r["User"]["username"]));
			?>
		</p>
		<small>
			<?php
				//直近のツイートを表示
				if( !empty($latest_tweets[$r["User"]["id"]]) ) {
					echo $latest_tweets[$r["User"]["id"]]["Tweet"]["content"];
					echo ' '. $latest_tweets[$r["User"]["id"]]["Tweet"]["time"];
				} else {
					echo "";	//ツイート数0のとき
				}
			?>
		</small>
	</div>
<?php } ?>

<div class=paging>
	<?php
	echo $this->Paginator->prev('< 前へ', array(), null, array('class' => 'prev disabled'));
	//echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next('次へ >', array(), null, array('class' => 'next disabled'));
?>
</div>