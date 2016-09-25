<?php

class AppControllerTest extends ControllerTestCase {
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
	
	
	public function testUserStatus(){
		$this->testAction('/tweets/index', array( 'return' => 'vars'));
		$this->assertArrayHasKey('username', $this->vars);
		$this->assertArrayHasKey('tweets_num', $this->vars);
		$this->assertArrayHasKey('followers_num', $this->vars);
		$this->assertArrayHasKey('following_num', $this->vars);
		
		$this->testAction('/tweets/posts/aaa', array( 'return' => 'vars'));
		$this->assertArrayHasKey('username', $this->vars);
		$this->assertArrayHasKey('tweets_num', $this->vars);
		$this->assertArrayHasKey('followers_num', $this->vars);
		$this->assertArrayHasKey('following_num', $this->vars);
		
		
	}
	
}

