<?php
App::uses('AppController', 'Controller');

class FollowsController extends AppController {


	//不要？
  public function index($username){
		$this->redirect('/tweets/index');
  }
  
	public function follows($username){
		$this->loadModel('User');
		if( empty($this->User->find( 'count', array( 'conditions' => array('username' => $username))))){
			return $this->redirect('/tweets/index');
		}
		
		//$usernameさんのidを入手
		$user_id = $this->User->usernameToId( $username );
		$this->set('user_id', $user_id );
		
		//$usernameさんのフォロー情報を渡す
		$following_ids = $this->Follow->getFollowingIds( $user_id );
		$followUsers = $this->User->pageUsers( $following_ids, 1 );
		$this->set('users', $followUsers);
		
		//フォローボタン用
		$this->set( 'following_ids', $this->Follow->getFollowingIds( $this->Auth->user()['id'] ) );
    
		//$usernameさんの情報（f/f、つぶやき数）をset
		$this->setUserStatus( $user_id );
		
		//フォローしてる人たちの最新のつぶやき
		$this->loadModel('Tweet');
		$latest_tweets = array();
		foreach( $followUsers as $u ) {
			$tweet = $this->Tweet->getLatest( $u["User"]["id"] );
			$latest_tweets[$u["User"]["id"]] = $tweet;
		}
		$this->set( 'latest_tweets', $latest_tweets );
	}
	
	public function followers($username){
		$this->loadModel('User');
		if( empty($this->User->find( 'count', array( 'conditions' => array('username' => $username))))){
			return $this->redirect('/tweets/index');
		}
		//$usernameさんのidを入手
		$user_id = $this->User->usernameToId( $username );
		$this->set('user_id', $user_id );
		
		//フォロワー情報
		$follower_ids = $this->Follow->getFollowerIds( $user_id );
		$followerUsers = $this->User->pageUsers( $follower_ids, 1 );
		$this->set('users', $followerUsers);
		
		//フォローボタン用
		$this->set( 'following_ids', $this->Follow->getFollowingIds( $this->Auth->user()['id'] ) );
		
		//$usernameさんの情報（f/f、つぶやき数）をset
		$this->setUserStatus( $user_id );
		
		//フォロワーたちの最新のつぶやき
		$this->loadModel('Tweet');
		$latest_tweets = array();
		foreach( $followerUsers as $u ) {
			$tweet = $this->Tweet->getLatest( $u["User"]["id"] );
			$latest_tweets[$u["User"]["id"]] = $tweet;
		}
		$this->set( 'latest_tweets', $latest_tweets );
	}
	
	
}