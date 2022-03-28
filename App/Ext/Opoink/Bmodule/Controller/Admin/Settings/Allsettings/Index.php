<?php
namespace Opoink\Bmodule\Controller\Admin\Settings\Allsettings;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Settings';
	protected $_url;
	protected $_message;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message
	){

		$this->_url = $Url;
		$this->_message = $Message;
	}

	public function run(){
		return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
