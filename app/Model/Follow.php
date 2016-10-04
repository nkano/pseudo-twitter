<?php
App::uses('AppModel', 'Model');

class Follow extends AppModel {
	/*
	//アソシエーション
	public $belongsTo = array(
		'Followee' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			
		),
			'Follower' => array(
			'className' => 'User',
			'foreignKey' => 'follow_id',
		)
	);
	*/
	
	public $validate = array(
  	'user_id' => array (
  		array(
  			'rule' => array('isUnique', array('user_id', 'follow_id'), false),
  			'message' => 'user_idとfollow_idの組み合わせは一意でなければなりません'
  		),
  		array(
  			'rule' => 'inableSelfFollow',
  			'message' => '自分自身をフォローできません'
  		)
  	)
  );
	
	public function inableSelfFollow( $check ) {
		return ( $this->data['Follow']['user_id'] != $this->data['Follow']['follow_id'] );
	}
	
	//$user_idがフォローしてる人のidを返す
	public function getFollowingIds( $user_id, $add_himself = false ) {
		$follows = $this->find( 'all', array(
			'conditions' => array( 'user_id' => $user_id ),
			'order' => array('follow_id' => 'desc')
		));
		$follow_ids = ($add_himself)? array( $user_id ) : array();
		foreach( $follows as $f ) {
			$follow_ids[] = $f["Follow"]["follow_id"];	//フォローしてる人のIDを入れる
		}
		return $follow_ids;
	}
	
	//$user_idをフォローしてる人のidを返す
	public function getFollowerIds( $user_id ) {
		$followers = $this->find( 'all', array(
			'conditions' => array( 'follow_id' => $user_id ),
			'order' => array('user_id' => 'desc')
		));
		$follower_ids = array();
		foreach( $followers as $f ) {
			$follower_ids[] = $f["Follow"]["user_id"];	//フォローしてる人のIDを入れる
		}
		return $follower_ids;
	}
	
	public function countFollowing( $user_id ) {
		$conditions = array( 'user_id' => $user_id );
		return $this->find( 'count', array( 'conditions' => $conditions ) );
	}
	
	public function countFollower( $follow_id ) {
		$conditions = array( 'follow_id' => $follow_id );
		return $this->find( 'count', array( 'conditions' => $conditions ) );
	}
	
}
