<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

namespace Opoink\Bmodule\Controller\Admin\Login\Index;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Admin Login';
	protected $_url;
	protected $_message;

	/**
	 * \Opoink\Bmodule\Entity\AdminsEntity
	 */
	protected $_adminsEntity;

	/**
	 * \Of\Std\Password
	 */
	protected $_password;

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Entity\Admins $AdminsEntity,
		\Of\Std\Password $Password,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_adminsEntity = $AdminsEntity;
		$this->_password = $Password;
		$this->_session = $Session;
	}

	public function run(){
		$this->_session->requireNotLoggedIn();

		$email = $this->getParam('email');
		$password = $this->getParam('password');

		if($email && $password){

			$adminUser = $this->_adminsEntity->getByColumn([
				'email' => $email
			]);

			if($adminUser){
				if($adminUser->getData('invalid_login_count') > 5){
					/**
					 * TODO: block IP address from doing login for 5min or what is set in the settings
					 */
				}


				$isValid = $this->_password->setPassword($password)
				->setHash($adminUser->getData('password'))
				->verify();

				if($isValid){
					$adminUser->setData('invalid_login_count', 0);
					$adminUser->save();

					$adminUser->removeData('password');

					$this->_session->setData('admin_user', $adminUser->getData());

					$this->toJson([
						"user" => $adminUser->getData()
					]);
				}
				else {
					$adminUser->setData('invalid_login_count', $adminUser->getData('invalid_login_count') + 1);
					$adminUser->save();
					$this->returnError(401, 'Invalid email address or password');
				}
			}
			else {
				$this->returnError(401, 'Invalid email address or password');
			}
		}
		else {
			return parent::run();
		}
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
