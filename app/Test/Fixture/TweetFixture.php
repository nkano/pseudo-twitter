﻿<?php
class TweetFixture extends CakeTestFixture {
	
  public $useDbConfig = 'test';
  public $fields = array(
    'id'	=> array('type' => 'integer', 'key' => 'primary'),
    'user_id'	=> array('type' => 'integer'),
    'content'	=> array('type' => 'string', 'length' => 140),
    'time' => array('type' => 'datetime')
  );
  public $records = array(
    array('id' => 1, 'user_id' => 1, 'content' => 'old tweet', 'time' => '2016-09-23 03:06:00' ),
    array('id' => 2, 'user_id' => 2, 'content' => 'bbb tweeted this', 'time' => '2016-09-23 03:07:00' ),
      array('id' => 3, 'user_id' => 1, 'content' => 'new tweet', 'time' => '2016-09-23 03:08:00' )
	);

}