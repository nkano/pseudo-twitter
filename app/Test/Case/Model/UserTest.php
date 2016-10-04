<?php

App::uses('AuthComponent', 'Controller/Component');

class UserTest extends CakeTestCase {
	public $fixtures = array('app.user', 'app.tweet', 'app.follow');
	
	public function setUp() {
		$this->User = ClassRegistry::init('User');
		
	}
	
	
	private function add( $username, $password, $mail, $nickname='' ) {
		return $this->User->create(array(
          'username' => $username,
          'password' => $password,
          'mail' => $mail,
          'nickname' => $nickname
        ));
	}
	
  public function testValidation(){
  	//should be valid insertion
    $this-> add('uniqueName', 'aaaaaaaaaaaa', 'aaa@aaa.com');
    $results = $this->User->invalidFields();
		$this->assertArrayNotHasKey('username', $results);
    $this->assertArrayNotHasKey('password', $results);
    $this->assertArrayNotHasKey('mail', $results);
    
    // alphaNumeric
    $this-> add('日本語日本語日本語', '日本語日本語日本語', 'aaa@aaa.com');
    $results = $this->User->invalidFields();
		$this->assertArrayHasKey('username', $results);
    $this->assertArrayHasKey('password', $results);
    
    // isnt too short?
    $this-> add('a', 'a', 'aaa@aaa.com');
    $results = $this->User->invalidFields();
		$this->assertArrayHasKey('username', $results);
    $this->assertArrayHasKey('password', $results);
    
    // isnt too long?
    $this-> add(str_repeat("a", 33), str_repeat("a", 33), 'aaa@aaa.com');
    $results = $this->User->invalidFields();
		$this->assertArrayHasKey('username', $results);
    $this->assertArrayHasKey('password', $results);
    
    //username is unique?
    //mail is valid?
    $this-> add('aaa', 'aaaaaaaaaa', 'thisisnotmailaddress');
    $results = $this->User->invalidFields();
		$this->assertArrayHasKey('username', $results);
		$this->assertArrayHasKey('mail', $results);
    
    //nickname isnt too long?
    $this-> add('uniqueName', 'aaaaaaaaaa', 'aaa@aa.com', str_repeat('a',63));
    $results = $this->User->invalidFields();
    $this->assertArrayHasKey('nickname', $results);
		
  }
  
  public function testUsernameToId() {
  	$this->assertEquals( 1, $this->User->usernameToId( 'aaa' ) );
  }
  
	public function testUserIdToName() {
  	$this->assertEquals( 'aaa', $this->User->userIdToName( 1 ) );
  }
  
  public function testUsernameLikeToIds() {
  	$this->assertEquals( array(1), $this->User->usernameLikeToIds( 'aa' ) );
  	$this->assertEquals( array(1,2,3), $this->User->usernameLikeToIds( '' ) );
  }
  
  public function testPageUsers() {
  	$this->assertCount( 2, $this->User->pageUsers( array(1,2), 1 ) );
  	$this->assertCount( 0, $this->User->pageUsers( array(1,2), 2 ) );
  	
  	//limit=1
  	$this->assertCount( 1, $this->User->pageUsers( array(1,2), 1, 1 ) );
  }
}
?>