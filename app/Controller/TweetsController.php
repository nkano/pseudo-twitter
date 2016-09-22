<?php
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


	//ajaxでつぶやく
	public function add_tweet(){
		$this->layout = "";
		if ($this->request->is('post') && $this->request->is('ajax')) {
			//saveの返り値がなぜかtime情報を持ってないのでここで追加するが、
			//Timezoneを設定しないとずれるので設定しておく
			date_default_timezone_set('Asia/Tokyo');
			
			$saveData = array(
				'Tweet' => array(
					'user_id' => $this->Auth->user()['id'],
					'content' => $this->request->data['tweet'],
					'time' => date('Y-m-d H:i:s')
				)
			);
			$saveData['User'] = $this->Auth->user();
			//DBに保存
			$savedData = $this->Tweet->save($saveData);
			
			if ($savedData) {
				$this->set('tweet', $savedData);
				
			} else {
				$this->Session->setFlash('投稿は140文字以内です');
				
			}
			
		}
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
			
			$options = array( "conditions" => array( 'user_id' => $ids  ),
    			'order' => array('Tweet.id' => 'desc'),
   	 			'limit' => 10,
    			'page' => $this->request->query["page_num"] );
			$tweets = $this->Tweet->find( 'all', $options);
			$this->set('tweets', $tweets);
			
			//debug($this->request->query);
		}
		
	}
	
	//定期的にツイート更新
	public function load_new_tweets() {
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
			$t = str_replace( "%20", " ", $this->request->query["current_tweet_date"]);
			$t = str_replace( "%3A", ":", $t);
			
			$options = array( 
				"conditions" => array( 'user_id' => $ids, 'time >' => $t),
    		'order' => array('Tweet.id' => 'desc') );
    	
			$tweets = $this->Tweet->find( 'all', $options);
			$this->set('tweets', $tweets);
			
			//debug($this->request->query);
		}
		
	}
	
}