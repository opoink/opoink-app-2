<?php
namespace Opoink\Bmodule\Controller\Admin\Logout\Index;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Logout';
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
		$this->_session->logout();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
