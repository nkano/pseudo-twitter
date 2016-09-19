<?php
App::uses('AppController', 'Controller');

class FollowsController extends AppController {
  public $paginate = array(
    'Follow' => array(
    	'limit' =>10,
    	'order' => array('added' => 'desc')
     )
	);

	//不要？
  public function index($username){
		$this->redirect('/tweets/index');
  }
  
	//todo:follows()とfollowers()の共通部分をbeforeFilterにいれるとかしてすっきりさせる
	public function follows($username){
		//$usernameさんのidを入手
		$this->loadModel('User');
		$user_id = $this->User->usernameToId( $username );
		$this->set('user_id', $user_id );
		
		//$usernameさんのフォロー情報を渡す
		
		$conditions = array('user_id' => $user_id );
		$follows = $this->paginate($conditions);
    $this->set('follows', $follows);
    
		//$usernameさんの情報（f/f、つぶやき数）をset
		$this->setUserStatus( $user_id );
		
		//フォローしてる人たちの最新のつぶやき
		$this->loadModel('Tweet');
		$latest_tweets = array();
		foreach( $follows as $f ) {
			$tweet = $this->Tweet->getLatest( $f["Follower"]["id"] );
			$latest_tweets[$f["Follower"]["id"]] = $tweet;
		}
		$this->set( 'latest_tweets', $latest_tweets );
		//debug($follows);
	}
	
	public function followers($username){
		//$usernameさんのidを入手
		$this->loadModel('User');
		$user_id = $this->User->usernameToId( $username );
		$this->set('user_id', $user_id );
		
		//$usernameさんがフォローしてる人一覧をViewに渡す
		//（$usernameさんがuser_idである要素を取り出したFollowテーブルを渡す）
		// ※フォローボタン表示のために必要
		$conditions = array('user_id' => $user_id );
		$follows = $this->paginate($conditions);
    $this->set('follows', $follows);
		
		//$usernameさんをフォローしてる人一覧をViewに渡す
		//（$usernameさんがfollow_idである要素を取り出したFollowテーブルを渡す）
		$conditions = array('follow_id' => $user_id );
		$followers = $this->paginate($conditions);
    $this->set('followers', $followers);
		
		//$usernameさんの情報（f/f、つぶやき数）をset
		$this->setUserStatus( $user_id );
		
		//フォロワーたちの最新のつぶやき
		$this->loadModel('Tweet');
		$latest_tweets = array();
		foreach( $followers as $f ) {
			$tweet = $this->Tweet->getLatest( $f["Followee"]["id"] );
			$latest_tweets[$f["Followee"]["id"]] = $tweet;
		}
		$this->set( 'latest_tweets', $latest_tweets );
	}
	
	
}