$( function() {
	$("#TweetIndexForm").submit(function() {
		console.log("submit");
		$.post('/cakephp/tweets/add_tweet', {
			tweet: $("#TweetContent").val()
		}, function(data) {
			//tweet成功時の処理
			$("#tweetlist").prepend(data);
			
			//最新のつぶやき更新
			$("#latest_tweet").text('最新のつぶやき: '
				+ $(".tweet_content", data).text()
				+ $(".tweet_date", data).text());
			
			//Formの入力値を消す
			$("#TweetContent").val('');
		});
	});
	


});
