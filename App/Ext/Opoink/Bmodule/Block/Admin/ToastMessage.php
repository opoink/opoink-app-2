<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin;

class ToastMessage extends \Opoink\Bmodule\Block\Admin\Context {
	
	/**
	 * \Of\Std\Message
	 */
	protected $_message;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Of\Std\Message $Message
	){
		parent::__construct($Url, $Config, $Lang);
		$this->_message = $Message;
	}
}
?>