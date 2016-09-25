<?php
class UserFixture extends CakeTestFixture {
	
  public $useDbConfig = 'test';
  public $fields = array(
    'id'	=> array('type' => 'integer', 'key' => 'primary'),
    'username'	=> array('type' => 'string', 'length' => 32),
    'password'	=> array('type' => 'string', 'length' => 32),
    'mail'	=> array('type' => 'string'),
    'public' => array('type' => 'boolean', 'default' => 0, 'null' => true),
    'nickname' => array('type' => 'string', 'default' => '', 'null' => true)
  );
  public $records = array(
    array('id' => 1, 'username' => 'aaa',  'password' => 'aaaaaaaa', 'mail' => 'aaa@aaa.com'),
    array('id' => 2, 'username' => 'bbb',  'password' => 'aaaaaaaa', 'mail' => 'aaa@aaa.com'),
   array('id' => 3, 'username' => 'ccc',  'password' => 'aaaaaaaa', 'mail' => 'aaa@aaa.com'),
  );

}