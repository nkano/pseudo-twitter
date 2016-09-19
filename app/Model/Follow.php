<?php
App::uses('AppModel', 'Model');

class Follow extends AppModel {
	//アソシエーション。重くなったらbindModel()
	public $belongsTo = array(
		'Followee' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			//'counterCache' => true	//ここには使えないっぽい？
			
		),
			'Follower' => array(
			'className' => 'User',
			'foreignKey' => 'follow_id',
			//'counterCache' => true
		)
	);
	
	
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
