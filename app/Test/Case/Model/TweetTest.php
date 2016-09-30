<?php

App::uses('AuthComponent', 'Controller/Component');

class TweetTest extends CakeTestCase {
	public $fixtures = array('app.tweet', 'app.user');
	
	public function setUp() {
		$this->Tweet = ClassRegistry::init('Tweet');
	}
	
	
	private function add( $user_id, $content, $time ) {
		return $this->Tweet->create(array(
          'user_id' => $user_id,
          'content' => $content,
          'time' => $time
        ));
	}
	
  public function testValidation(){
  	//should be valid insertion
    $this-> add(1, str_repeat('a',140), '2016-09-23 03:08:10');
    $results = $this->Tweet->invalidFields();
		$this->assertArrayNotHasKey('user_id', $results);
    $this->assertArrayNotHasKey('content', $results);
    $this->assertArrayNotHasKey('time', $results);
    
    // type check
    $this-> add('idIsntInteger', 'valid tweet', 'timeIsntTimestamp');
    $results = $this->Tweet->invalidFields();
		$this->assertArrayHasKey('user_id', $results);
    $this->assertArrayHasKey('time', $results);
    
    // tweet isnt too long?
    $this-> add(1, str_repeat('a', 141), '2016-09-23 03:08:10');
    $results = $this->Tweet->invalidFields();
		$this->assertArrayHasKey('content', $results);
		
		// isnt blank?
    $this-> add('', '', '');
    $results = $this->Tweet->invalidFields();
		$this->assertArrayHasKey('user_id', $results);
		$this->assertArrayHasKey('content', $results);
    $this->assertArrayHasKey('time', $results);
  }
  
  public function testGetLatest() {
  	$latest = $this->Tweet->getLatest( 1 );
  	$this->assertEquals( 'new tweet', $latest['Tweet']['content'] );
  }
  
  public function testCountTweetNum() {
  	$this->assertEquals( 2, $this->Tweet->countTweetNum(1) );
  }
  
  public function testPageTweets() {
  	$tweets = $this->Tweet->pageTweets( array(1,2), 1 );
  	$this->assertCount(3, $tweets);
  	$tweets = $this->Tweet->pageTweets( array(1), 1 );
  	$this->assertCount(2, $tweets);
  	$tweets = $this->Tweet->pageTweets( array(1,2), 2 );
  	$this->assertCount(0, $tweets);
  	
  }
  
}
?>