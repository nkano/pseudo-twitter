﻿<?php
App::uses('AppModel', 'Model');

class Tweet extends AppModel {
	//アソシエーション。重くなったらbindModel()
	
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			//'counterCache' => true
		)
	);
	
	
  public $validate = array(
  	'user_id' => array (
  		array(
  			'rule' => 'numeric',
  			'message' => 'user_idが数値ではありません'
  			)
  	),
		'content' => array (
      array(
        'rule' => array('between', 1, 140),
        'message' => '140文字を越えています'
			)
		),
		'time' => array (
			array(
				'rule' => array('datetime', 'ymd'),
				'message' => '日時が不正です。'
			)
		)
  );
  
	//最新のつぶやきを返す
  public function getLatest( $user_id ) {
  	$conditions = array( 'user_id' => $user_id );
  	return $this->find('first', array(
 			'conditions' => $conditions,
			'order' => array( 'time' => 'desc' )
		));
  }
  
  public function getLatests( $user_ids ) {
  	$latest_tweets = array();
		foreach( $user_ids as $id ) {
			$tweet = $this->getLatest( $id );
			$latest_tweets[$id] = $tweet;
		}
  	return $latest_tweets;
  }
  
  
  public function countTweetNum( $user_id ) {
  	$conditions = array( 'user_id' => $user_id );
		return $this->find( 'count', array( 'conditions' => $conditions ) );
  }
  
  public function pageTweets( $ids, $page, $offset = 0, $limit = 10 ) {
  	$options = array( "conditions" => array( 'user_id' => $ids ),
    	'order' => array('Tweet.time' => 'desc'),
    	'limit' => $limit,
    	'page' => $page,
    	'offset' => $offset );
		return $this->find( 'all', $options);
  }
  
}
