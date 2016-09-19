<?php
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
		'content' => array (
      array(
        'rule' => array('between', 1, 140),
        'message' => '140文字を越えています'
			)
		)
  );
  
	//最新のつぶやきを返す
  public function getLatest( $user_id ) {
  	$conditions = array( 'user_id' => $user_id );
  	return $this->find('first',
			array( 'conditions' => $conditions, 'order' => array( 'time' => 'desc' ) )
		);
  }
  
}
