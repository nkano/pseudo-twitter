<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array('Auth');

	public function beforeFilter() {
		$this->Auth->allow();
		$this->set('user', $this->Auth->user());
		$this->layout = 'main';
	}
	
	//ユーザー情報（名前、f/f、ツイート数）を算出してsetもする関数
	//View側ではそれぞれ$username, $following_num, $followers_num, $tweets_num
	//（memo: setもここでするのは混乱するかも？）
	public function setUserStatus($user_id) {
		$this->loadModel('User');
		$this->loadModel('Follow');
		$this->loadModel('Tweet');
		
		//名前
		$conditions = array( 'id' => $user_id );
		$username = $this->User->find('first', array( 'conditions' => $conditions ) )['User']['username'];
		$this->set('username', $username);
		
		//select * from follows where user_id == 自分のID
		$conditions = array( 'user_id' => $user_id );
		$following_num = $this->Follow->find( 'count', array( 'conditions' => $conditions ) );
		$this->set('following_num', $following_num );
		//select * from follows where follow_id == 自分のID
		$conditions = array( 'follow_id' => $user_id );
		$followers_num = $this->Follow->find( 'count', array( 'conditions' => $conditions ) );
		$this->set('followers_num', $followers_num );
		//つぶやき数をViewに送る
		$conditions = array( 'user_id' => $user_id );
		$tweets_num = $this->Tweet->find( 'count', array( 'conditions' => $conditions ) );
		$this->set('tweets_num', $tweets_num );
	}
	
}
