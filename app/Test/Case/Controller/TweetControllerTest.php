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
		$this->testAction('/tweets/index', array( 'return' => 'vars'));
		debug($this->vars);	//とりあえずsetした変数を表示
	}
	
	
	
	
	
}

