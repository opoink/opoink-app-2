<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Controller\Admin\Index\Index;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Dashboard';
	protected $_url;
	protected $_message;

	/**
	 * \Of\Session\Session
	 */
	protected $_session;
	protected $admins;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\Admins $Admins
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_session = $Session;
		$this->admins = $Admins;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		// $data = [];

		// for ($i=0; $i < 1000000; $i++) { 
		// 	$u = 'testing__'.$i;

		// 	$data = [
		// 		'email' => $u.'@opoink.com',
		// 		'username' => $u,
		// 		'firstname' => $u,
		// 		'lastname' => $u,
		// 		'password' => $u,
		// 		'invalid_login_count' => $u,
		// 		'status' => 'enabled',
		// 		'lang' => 'en',
		// 	];

		// 	$this->admins->setData($data)->save();
		// }

		return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
