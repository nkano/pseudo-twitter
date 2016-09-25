<?php
class FollowFixture extends CakeTestFixture {
	
  public $useDbConfig = 'test';
  public $fields = array(
    'user_id'	=> array('type' => 'integer'),
    'follow_id'	=> array('type' => 'integer'),
    'added' => array('type' => 'datetime')
  );
  public $records = array(
    array('user_id' => 1, 'follow_id' => 2, 'added' => '2016-09-23 03:06:00'),
    array('user_id' => 1, 'follow_id' => 3, 'added' => '2016-09-23 03:07:00'),
    array('user_id' => 2, 'follow_id' => 1, 'added' => '2016-09-23 03:08:00'),
	);

}