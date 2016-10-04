<?php
App::uses('AppController', 'Controller');


class UsersController extends AppController {
	public $components = array('Session', 'Auth');
	
  public function beforeFilter() {
    parent::beforeFilter();

		//ログイン済みでなければアクセスできないアクションを指定
    $this->Auth->deny('index', 'logout');
  }

	//不要？
  public function index(){
    $this->redirect('/tweets/index');
  }

  public function register(){
    //$this->requestにPOSTされたデータが入っている
    //POSTメソッドかつユーザ追加が成功したら
    if($this->request->is('post') && $this->User->save($this->request->data)){
      //ログイン
      //$this->request->dataの値を使用してログインする規約になっている
      $this->Auth->login();
      $this->redirect('/tweets/index');
    }
  }

  public function login(){
    if($this->request->is('post')) {
    	
      if($this->Auth->login()) {
        return $this->redirect('/tweets/index');
			} else {
        $this->Session->setFlash('ログイン失敗');
			}
    }
  }

  public function logout(){
    $this->Auth->logout();
    $this->redirect('login');
  }
  
  
	public function search() {
		
  }
	
	public function search_result() {
		if($this->request->is('get')) {
			$query = $this->request->query('username');
	    $this->set('query', $query);
			
			$ids = $this->User->usernameLikeToIds($query);
			$users = $this->User->pageUsers( $ids, 1 );
			
	    $this->set('users', $users);
	    if( count($ids) >= 1 ) {
			
				$this->Session->setFlash( h($query).'の検索結果は'.count($ids).'件です');
			
				//検索に引っかかった人たちの最新のつぶやき
				$this->loadModel('Tweet');
				$latest_tweets = array();
				foreach( $users as $u ) {
					$tweet = $this->Tweet->getLatest( $u["User"]["id"] );
					$latest_tweets[$u["User"]["id"]] = $tweet;
				}
				$this->set( 'latest_tweets', $latest_tweets );
			
			} else {
				$this->Session->setFlash( '対象のユーザは見つかりません。');	//todo:赤字
			}
		}
		
		if( !empty($this->Auth->user()) ) {
			$this->loadModel("Follow");
			$this->set( 'following_ids', $this->Follow->getFollowingIds( $this->Auth->user()['id'] ) );
		}
		
	}
	
	//フォローする
	public function add_follow() {
		if($this->request->is('post')) {
			$this->loadModel("Follow");
			//saveメソッドに投げるためのフォーマットを整える
			$tmp = array_merge( array(  "user_id" =>$this->Auth->user()["id"] ), $this->request->data );
			$savedata = array( "Follow" => $tmp );
			$this->Follow->save($savedata);
			return $this->redirect('/tweets/index');
		}
	}
	//アンフォローする
	public function delete_follow() {
		if($this->request->is('post')) {
			$this->loadModel("Follow");
			//deleteAllメソッドに投げるためのフォーマットを整える
			$conditions = array('user_id' => $this->Auth->user()["id"], 
					'follow_id' => $this->request->data['follow_id']);
			$this->Follow->deleteAll($conditions, false);
			return $this->redirect('/tweets/index');
		}
	}
	
	
	//スクロールして次ページのユーザーを表示
	public function expand_user_list() {
		if ($this->request->is('get') && $this->request->is('ajax')) {
			$this->layout = "";
			$splitted_location = explode( "/", $this->request->query["current_location"] );
			if( $splitted_location[0] == "search_result" ) {
				//検索IDを取得
				$ids = $this->User->usernameLikeToIds( $splitted_location[1] );
				
			} else if( $splitted_location[0] == "follows" ) {
				$this->loadModel( 'Follow' );
				$user_id = $this->User->usernameToId( $splitted_location[1] );
				$ids = $this->Follow->getFollowingIds( $user_id );
			} else if( $splitted_location[0] == "followers" ){
				$this->loadModel( 'Follow' );
				$user_id = $this->User->usernameToId( $splitted_location[1] );
				$ids = $this->Follow->getFollowerIds( $user_id );
			} else {
				//不正なGETパラメータ
				return false;
			}
			
			$page = $this->request->query["page_num"];
			$users = $this->User->pageUsers( $ids, $page );
			$this->set('users', $users);
			
			$this->loadModel('Tweet');
			$latest_tweets = array();
			foreach( $users as $u ) {
				$tweet = $this->Tweet->getLatest( $u["User"]["id"] );
				$latest_tweets[$u["User"]["id"]] = $tweet;
			}
			$this->set( 'latest_tweets', $latest_tweets );
			
			if( !empty($this->Auth->user()) ) {
				$this->loadModel("Follow");
				$this->set( 'following_ids', $this->Follow->getFollowingIds( $this->Auth->user()['id'] ) );
		}
			
		}
		
	}
	
	
	
}