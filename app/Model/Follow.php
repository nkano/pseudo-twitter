<?php
App::uses('AppModel', 'Model');

class Follow extends AppModel {
	//アソシエーション。重くなったらbindModel()
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
		$conditions = array( 'user_id' => $user_id );
		$follows = $this->find( 'all', compact( "conditions" ) );
		$follow_ids = ($add_himself)? array( $user_id ) : array();
		foreach( $follows as $f ) {
			$follow_ids[] = $f["Follower"]["id"];	//フォローしてる人のIDを入れる
		}
		return $follow_ids;
	}
	
}
