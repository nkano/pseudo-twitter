<?php

App::uses('AuthComponent', 'Controller/Component');

class UserTest extends CakeTestCase {
  public function testValidation(){
    $this->User = ClassRegistry::init('User');
    
    $this->User->create(array(
          'username' => 'testUser',
          'password' => '',
          'mail' => ''
          
        ));
    $results = $this->User->invalidFields();

    $this->assertEquals(array_key_exists('username', $results), false);
    $this->assertEquals(array_key_exists('password', $results), true);
    $this->assertEquals(array_key_exists('mail', $results), true);
  }
  
}
?>