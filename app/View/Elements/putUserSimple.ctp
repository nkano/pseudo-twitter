<div class=userSimple>
	<div class=follow>
		<?php
			//フォローボタン
			if( !empty( $authUser ) and $authUser["id"] != $user["User"]["id"] ) {
				
				//フォロー済みかチェック
				if( in_array( $user["User"]["id"], $following_ids ) ) {
					echo "フォロー済み";
				} else {
					//未フォローならボタンを表示する
					echo $this->Form->postButton("follow", "/Users/add_follow/",
							array('data'=> array('follow_id'=>$user["User"]["id"])));
				}
			}
		?>
	</div>
	<p>
		<?php
		//名前
		echo $this->Html->link(h($user["User"]["username"]), "/tweets/posts/"
			. h($user["User"]["username"]));
		?>
	</p>
	<small>
		<?php
			//直近のツイートを表示
			if( !empty($latest_tweets[$user["User"]["id"]]) ) {
				echo $latest_tweets[$user["User"]["id"]]["Tweet"]["content"];
				echo ' '. $latest_tweets[$user["User"]["id"]]["Tweet"]["time"];
			} else {
				echo "";	//ツイート数0のとき
			}
		?>
	</small>
</div>