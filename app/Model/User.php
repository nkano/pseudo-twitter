﻿<?php
App::uses('AppModel', 'Model');

class User extends AppModel {
	//アソシエーション。重くなったらbindModel()
	/*
	public $hasMany = array(
		'FollowingThem' => array(
			'className' => 'Follow',
			'foreignKey' => 'user_id'
		),
		'FollowingMe' => array(
			'className' => 'Follow',
			'foreignKey' => 'follow_id'
		),
		'Tweet' => array(
			'className' => 'Tweet',
			'foreignKey' => 'user_id'
		)
	);
	*/
	
  //入力チェック機能
  public $validate = array(
    'username' => array(
      array(
        'rule' => 'isUnique', //重複禁止
        'message' => '既に使用されている名前です。'
      ),
      array(
        'rule' => 'alphaNumeric', //半角英数字のみ
        'message' => '名前は半角英数字にしてください。'
      ),
      array(
        'rule' => array('between', 2, 32), //2～32文字
        'message' => '名前は2文字以上32文字以内にしてください。'
      )
    ),
    'password' => array(
      array(
        'rule' => 'alphaNumeric',
        'message' => 'パスワードは半角英数字にしてください。'
      ),
      array(
        'rule' => array('between', 8, 32),
        'message' => 'パスワードは8文字以上32文字以内にしてください。'
      )
    ),
    'mail' => array(
    	'rule' => 'email',
    	'message' =>'メールアドレスが正しくありません。'
    ),
    'nickname' => array(
    	'rule' => array('between', 0, 32),
    	'message' =>'ニックネームは32文字以内です。',
    	'allowEmpty' => true
    )
  );

  public function beforeSave($options = array()) {
    $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
    return true;
    
  }
  
  public function usernameToId( $username ) {
  	return $this->find('first', array("conditions"=>array("username"=>$username)))["User"]["id"];
  }
  
  public function userIdToName( $user_id ) {
  	return $this->find('first', array("conditions"=>array("id"=>$user_id)))["User"]["username"];
  }
  
  public function usernameLikeToIds( $query ) {
  	$results = $this->find('all', array(
			'conditions'=> array('username LIKE' => '%'.$query.'%' )
		));
		$ids = array();
		foreach( $results as $r ) {
			$ids[] = $r["User"]["id"];
		}
  	return $ids;
  }
  
	public function pageUsers( $ids, $page, $limit = 10 ) {
  	$options = array( "conditions" => array( 'id' => $ids ),
    	'order' => array('User.id' => 'desc'),
    	'limit' => $limit,
    	'page' => $page );
		return $this->find( 'all', $options);
  }
	
	
}