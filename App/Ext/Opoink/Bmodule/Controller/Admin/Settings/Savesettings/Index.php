<?php
namespace Opoink\Bmodule\Controller\Admin\Settings\Savesettings;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Savesettings';
	protected $_url;
	protected $_message;
	protected $_formValidate;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\FormValidate $FormValidate
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_formValidate = $FormValidate;
	}

	public function run(){

		$form_key = $this->getPost('form_key');
		$form_builder_form_name = $this->getPost('form_builder_form_name');

		$isValid = $this->_formValidate->validate($form_builder_form_name, $form_key);

		/**
		 * validate each field using form validate
		 */
		echo "<pre>";
		print_r($isValid);

		die;

		return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
