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
			
			$conditions = array('username LIKE' => '%'.$query.'%' );
			$result = $this->User->find('all', array('conditions'=> $conditions));
	    $this->set('result', $result);
	    //debug($this->request->params);
	    if( count($result) >= 1 ) {
			
				$this->Session->setFlash( h($query).'の検索結果は'.count($result).'件です');
			
				//検索に引っかかった人たちの最新のつぶやき
				$this->loadModel('Tweet');
				$latest_tweets = array();
				foreach( $result as $r ) {
					$tweet = $this->Tweet->getLatest( $r["User"]["id"] );
					$latest_tweets[$r["User"]["id"]] = $tweet;
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
	

}