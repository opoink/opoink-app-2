<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Controller\Admin\Adminuser\Index;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Admin User';
	protected $_url;
	protected $_message;

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_session = $Session;
	}

	public function run(){
		$this->_session->requireLoggedIn();
		return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
