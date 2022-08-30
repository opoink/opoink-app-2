<?php
namespace Opoink\Bmodule\Controller\Admin\Users\Index;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Users Index Index';
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
