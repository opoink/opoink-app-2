<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

namespace Opoink\Bmodule\Lib\Admin;

class UserSession extends \Of\Session\Session {

	/**
	 * \Opoink\Bmodule\Entity\AdminsEntity
	 */
	protected $_adminsEntity;
	/**
	 * \Of\Http\Url
	 */
	protected $_url;

	public function __construct(
		\Opoink\Bmodule\Entity\Admins $AdminsEntity,
		\Of\Http\Url $Url
	){
		parent::__construct();
		$this->_adminsEntity = $AdminsEntity;
		$this->_url = $Url;
	}

	/**
	 * return \Opoink\Bmodule\Entity\Admins if the user is loged in
	 * else return null
	 */
	public function isLoggedIn(){
		$admin_user = $this->getData('admin_user');

		if($admin_user){
			return $this->_adminsEntity->getByColumn([
				'admins_id' => $admin_user['admins_id']
			]);
		}
	}

	/**
	 * check if the user is logged in or not
	 * redirect to dashboard if looged in
	 */
	public function requireNotLoggedIn(){
		if($this->isLoggedIn()){
			$this->_url->redirect('');
		}
	}

	/**
	 * check if the user is logged in or not
	 * redirect to dashboard if looged in
	 */
	public function requireLoggedIn(){
		if(!$this->isLoggedIn()){
			$this->_url->redirect('/login');
		}
	}
}
?>