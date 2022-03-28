<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

namespace Opoink\Bmodule\Lib\Admin;

class UserSession extends \Of\Session\Session {


	/**
	 * if the user is logged in this variable will be
	 * \Opoink\Bmodule\Entity\AdminsEntity 
	 * with the data of the current admin user
	 * if not the this will be null;
	 */
	protected $adminUser = null;

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
	 * 
	 * we will store the user data on first load so that the other 
	 * blocks or class that will call this function 
	 * will not fetch the same data again and again
	 */
	public function getAdminUser(){
		if(!$this->adminUser) {
			$admin_user = $this->getData('admin_user');
			if($admin_user){
				$this->adminUser = $this->_adminsEntity->getByColumn([
					'admins_id' => $admin_user['admins_id']
				]);
			}
		}
		return $this->adminUser;
	}

	/**
	 * return \Opoink\Bmodule\Entity\Admins if the user is loged in
	 * else return null
	 */
	public function isLoggedIn(){
		return $this->getAdminUser();
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