<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin;

class MainHeader extends \Opoink\Bmodule\Block\Admin\Context {
	
	/**
	 * \Opoink\Bmodule\Lib\Admin\UserSession
	 */
	protected $_userSession;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Lib\Admin\UserSession $UserSession
	){
		parent::__construct($Url, $Config, $Lang);
		$this->_userSession = $UserSession;
	}

	public function getAdminUser(){
		return $this->_userSession->getAdminUser();
	}
}
?>