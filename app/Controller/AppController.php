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
		$this->set('username', $this->User->userIdToName($user_id));
		//フォロー数
		$this->set('following_num', $this->Follow->countFollowing($user_id) );
		//フォロワー数
		$this->set('followers_num', $this->Follow->countFollower($user_id) );
		//つぶやき数をViewに送る
		$this->set('tweets_num', $this->Tweet->countTweetNum( $user_id ) );
	}
	
}
