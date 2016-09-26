<?php

// cheat: makes is('ajax') true
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';	

class TweetControllerTest extends ControllerTestCase {
	public $fixtures = array( 'app.tweet', 'app.follow', 'app.user' );
	
	public function setUp() {
    parent::setUp();
    
    //ログインユーザーを設定
   	$Tweets = $this->generate('Tweets', array(
      'components' => array(
        'Auth' => array('user'),
      )
    ));
    $Tweets->Auth->staticExpects($this->any())
    	->method('user')
    	->will($this->returnValue(
    		array(
    			'id' => 1,
    			'username' => 'aaa',
    			'password' => 'aaaaaaaa',
    			'mail' => 'aaa@aaa.com'
    	)));
		
		
  }
	
	
	public function testIndex() {
		//画面表示
		//$this->testAction('/tweets/index', array( 'return' => 'view'));
		//pr($this->view);
		
		//setのテスト
		$this->testAction('/tweets/index', array( 'return' => 'vars'));
		//pr($this->vars);
		$this->assertArrayHasKey('latest_tweet', $this->vars);
		$this->assertArrayHasKey('tweets', $this->vars);
		$this->assertCount(3, $this->vars['tweets']);
	}
	
	
	public function testPosts() {
		//画面表示
		//$this->testAction('/tweets/posts/aaa', array( 'return' => 'view'));
		//pr($this->view);
		
		//setのテスト
		$this->testAction('/tweets/posts/aaa', array( 'return' => 'vars'));
		//pr($this->vars);
		$this->assertArrayHasKey('latest_tweet', $this->vars);
		$this->assertArrayHasKey('tweets', $this->vars);
		$this->assertCount(2, $this->vars['tweets']);
	}
	
	public function testAddTweet() {
		//正常ポスト
		$data = array( 'tweet' => 'test add tweet' );
		$this->testAction('/tweets/add_tweet', array(
			'data' => $data,
			'return' => 'vars'
		));
		$this->assertEquals( $data['tweet'], $this->vars['tweet']['Tweet']['content'] );
		
		//異常ポスト
		$data = array( 'tweet' => str_repeat('a', 141) );
		$this->testAction('/tweets/add_tweet', array(
			'data' => $data,
			'return' => 'vars'
		));
		$this->assertEquals( array(), $this->vars['tweet'] );
	}
	
	//todo
	public function testDeleteTweet() {
	}
	
	public function testExpandTweetList() {
		$data = array(
			'current_location' => 'index',
			'page_num' => '1'
		);
		$this->testAction('/tweets/expand_tweet_list', array(
			'data' => $data, 'method' => 'get', 'return' => 'vars'
		));
		$this->assertArrayHasKey('tweets', $this->vars);
		//pr($this->vars);
	}
	
	public function testLoadNewTweets() {
		//新しいツイートが1件
		$data = array(
			'current_location' => 'index',
			'current_tweet_date' => '2016-09-23 03:07:00'
		);
		$this->testAction('/tweets/load_new_tweets', array(
			'data' => $data, 'method' => 'get', 'return' => 'vars'
		));
		$this->assertCount(1, $this->vars['tweets']);
		
		//新しいツイートが0件
		$data = array(
			'current_location' => 'index',
			'current_tweet_date' => '2016-09-23 03:08:00'
		);
		$this->testAction('/tweets/load_new_tweets', array(
			'data' => $data, 'method' => 'get', 'return' => 'vars'
		));
		$this->assertCount(0, $this->vars['tweets']);
		
	}
}

