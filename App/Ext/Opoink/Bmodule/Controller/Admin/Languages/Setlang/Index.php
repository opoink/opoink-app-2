<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Controller\Admin\Languages\Setlang;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Languages Set Language';
	protected $_url;
	protected $_message;
	protected $_lang;

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session
	){
		$this->_url = $Url;
		$this->_message = $Message;
		$this->_lang = $Lang;
		$this->_session = $Session;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		$adminUser = $this->_session->getAdminUser();

		$lang = $this->getParam('lang');

		if($lang){
			$newLang = $this->_lang->saveLangToSession($lang);
			$adminUser->setData('lang', $newLang)->save();
		}

		$referer = $this->_request->getServer('HTTP_REFERER');
		if($referer){
			$this->_url->redirectTo($referer);
		}
		else {
			$this->_url->redirect('');
		}

		// return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
