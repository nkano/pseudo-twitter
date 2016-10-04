$(function() {
	var timer = null;
	$(window).on("scroll resize", function(){
		clearTimeout( timer );	//負荷軽減のためタイムアウト指定
		timer = setTimeout( function() {
			if( $(window).scrollTop() + window.innerHeight >= $(window).height() - 10 ) {
				//一番下までスクロールされたときの処理
				console.log("scrolled!");
				
				//呼ばれた場所(URI)を把握
				var current_location = $("#userlist").attr('data-current_location');
				
				//ページ番号を把握
				var page_num = Number($("#userlist").attr('data-page_num')) + 1;
				
				
				//リクエスト送信
				$.get("/cakephp/users/expand_user_list",
					{ page_num : page_num, current_location : current_location },
					function(data) {
						//リクエストが成功した時の処理
						//ツイート更新
						$(".userSimple:last").after(data);
						
						//ページ番号を更新
						$("#userlist").attr('data-page_num', page_num);
					}
				);
			}
		}, 300 );
	});
});