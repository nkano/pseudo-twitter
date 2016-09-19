﻿<?php
App::uses('AppController', 'Controller');

class TweetsController extends AppController {
	public $components = array('Session');
	
  public function beforeFilter() {
    parent::beforeFilter();
		
		//ログイン済みでなければアクセスできないアクションを指定
    $this->Auth->deny('index');
  }
	
	//ホーム画面
	public function index() {
		
		
		//フォローしてる人のidを取得
		$this->loadModel('Follow');
		$follow_ids = $this->Follow->getFollowingIds( $this->Auth->user()['id'], true);
		
		//フォローしてる人(+自分)のツイートをViewに送る
		//select * from tweets where user_id IN follow_ids;
		$options = array( "conditions" => array( 'user_id' => $follow_ids ),
    		'order' => array('Tweet.id' => 'desc'),
    		'limit' => 10,
    		'page' => 1 );
		$tweets = $this->Tweet->find( 'all', $options);
		$this->set('tweets', $tweets);
		
		//debug($tweets);
		
		//ユーザー（自分）情報をset
		$this->setUserStatus( $this->Auth->user()['id'] );
		
		//最新のつぶやき
		$latest_tweet = $this->Tweet->getLatest( $this->Auth->user()['id'] );
		$this->set( 'latest_tweet', $latest_tweet );
		
		

	}
	
	//ユーザごとのツイート画面
  public function posts($username){
		$this->loadModel('User');
		$user_id = $this->User->usernameToId( $username );
		
		//select * from tweets where user_id = $user_id
    $conditions = array( 'user_id' => $user_id );
    $tweets = $this->paginate($conditions);
		$this->set('tweets', $tweets);
   	$this->set('name', $username);
		
		//ユーザー情報をset
		$this->setUserStatus( $user_id );
		
		//最新のつぶやき
		$latest_tweet = $this->Tweet->getLatest( $user_id );
		$this->set( 'latest_tweet', $latest_tweet );
		
  }


	//つぶやく
	public function add_tweet(){
		
		if ($this->request->is('post')) {
			//ユーザIDを持ってくる
			$u = $this->Auth->user();
			$this->request->data["Tweet"]["user_id"] = $u["id"];
			//DBに保存
			if ($this->Tweet->save($this->request->data)) {
				return $this->redirect('/tweets/index');
			} else {
				$this->Session->setFlash('投稿は140文字以内です');
			}
		}
		$this->redirect('/tweets/index');
	}
	
	//tweetを削除
	public function delete_tweet($tweet_id) {
		if($this->request->is('post')){
			$this->Tweet->delete($tweet_id);
		}
		$this->redirect('/tweets/index');
	}
	
	
	//スクロールして次ページのツイートを表示
	public function expand_tweet_list() {
		if ($this->request->is('get') && $this->request->is('ajax')) {
			$this->layout = "";
			
			//このアクションが呼ばれた場所がindexかpostsかによって条件分岐
			//クエリ["current_location"]は"index"または"posts/ユーザ名"であるはず
			if( $this->request->query["current_location"] == "index" ) {
				//フォローしてる人のidを取得
				$this->loadModel('Follow');
				$ids = $this->Follow->getFollowingIds( $this->Auth->user()['id'], true);
			} else {
				$splitted_location = explode( "/", $this->request->query["current_location"] );
				if( $splitted_location[0] == "posts" ) {
					//posts画面のユーザのidを取得
					$this->loadModel('User');
					$ids = $this->User->usernameToId( $splitted_location );
				} else {
					//不正なGETパラメータ
					return false;
				}
			}
			
			$options = array( "conditions" => array( 'user_id' => $ids ),
    			'order' => array('Tweet.id' => 'desc'),
   	 			'limit' => 10,
    			'page' => $this->request->query["page_num"] );
			$tweets = $this->Tweet->find( 'all', $options);
			$this->set('tweets', $tweets);
			
			//debug($this->request->query);
		}
		
	}
	
}