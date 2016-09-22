$(function() {
	setInterval( function(){
		console.log("time");
		//呼ばれた場所(URI)を把握
		var current_location = $("#tweetlist").attr('data-current_location');
		//最新のツイートの投稿日時を把握
		var current_tweet_date = $(".tweet_date:first").text();
		//リクエスト送信
		$.get("/cakephp/tweets/load_new_tweets",
			{ current_location : current_location,
			  current_tweet_date : current_tweet_date },
			function(data) {
				//リクエストが成功した時の処理
				//ツイート更新
				$("#tweetlist").prepend(data);
				
			}
		);
	}, 10000);
});