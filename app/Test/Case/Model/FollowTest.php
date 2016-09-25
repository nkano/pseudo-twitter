<?php

App::uses('AuthComponent', 'Controller/Component');

class FollowTest extends CakeTestCase {
	public $fixtures = array('app.follow', 'app.user');
	
	public function setUp() {
		$this->Follow = ClassRegistry::init('Follow');
		
	}
	
	
	private function add( $user_id, $follow_id, $added = '2016-09-23 03:06:00' ) {
		return $this->Follow->create(array(
          'user_id' => $user_id,
          'follow_id' => $follow_id,
          'added' => $added,
        ));
	}
	
  public function testValidation(){
  	//should be valid insertion
    $this-> add(3, 1);
    $results = $this->Follow->invalidFields();
		$this->assertArrayNotHasKey('user_id', $results);
    $this->assertArrayNotHasKey('follow_id', $results);
    $this->assertArrayNotHasKey('added', $results);
    
		// isnt duplicate follow?
		$this-> add(1, 2);
    $results = $this->Follow->invalidFields();
		$this->assertArrayHasKey('user_id', $results);
    
    //isnt self follow?
    $this-> add(1, 1);
    $results = $this->Follow->invalidFields();
		$this->assertArrayHasKey('user_id', $results);
    
		
  }
  
  
  public function testGetFollowingIds() {
  	$ids = $this->Follow->getFollowingIds( 1 );
  	$this->assertEquals( array(2,3), $ids);
  	
  	$ids = $this->Follow->getFollowingIds( 3 );
  	$this->assertEquals( array(), $ids);
  	
  }
  
  public function testCounts() {
  	$this->assertEquals( 2, $this->Follow->countFollowing(1) );
  	$this->assertEquals( 1, $this->Follow->countFollower(1) );
  	
  }
  
}
?>