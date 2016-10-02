<?php
App::uses('AppController', 'Controller');

class FollowsController extends AppController {


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
		$following_ids = $this->Follow->getFollowingIds( $user_id );
		$followUsers = $this->User->find('all', array( "conditions" => array( 'id' => $following_ids )) );
		$this->set('users', $followUsers);
		$this->set('following_ids', $following_ids);
    
		//$usernameさんの情報（f/f、つぶやき数）をset
		$this->setUserStatus( $user_id );
		
		//フォローしてる人たちの最新のつぶやき
		$this->loadModel('Tweet');
		$latest_tweets = array();
		foreach( $following_ids as $f ) {
			$tweet = $this->Tweet->getLatest( $f );
			$latest_tweets[$f] = $tweet;
		}
		$this->set( 'latest_tweets', $latest_tweets );
		//debug($follows);
	}
	
	public function followers($username){
		//$usernameさんのidを入手
		$this->loadModel('User');
		$user_id = $this->User->usernameToId( $username );
		$this->set('user_id', $user_id );
		
		//フォロワー情報
		$follower_ids = $this->Follow->getFollowerIds( $user_id );
		$followerUsers = $this->User->find('all', array( "conditions" => array( 'id' => $follower_ids )) );
		$this->set('users', $followerUsers);
		
		//フォローボタン用フォロー情報
		$following_ids = $this->Follow->getFollowingIds( $user_id );
		$this->set('following_ids', $following_ids);
		
		//$usernameさんの情報（f/f、つぶやき数）をset
		$this->setUserStatus( $user_id );
		
		//フォロワーたちの最新のつぶやき
		$this->loadModel('Tweet');
		$latest_tweets = array();
		foreach( $follower_ids as $f ) {
			$tweet = $this->Tweet->getLatest( $f );
			$latest_tweets[$f] = $tweet;
		}
		$this->set( 'latest_tweets', $latest_tweets );
	}
	
	
}