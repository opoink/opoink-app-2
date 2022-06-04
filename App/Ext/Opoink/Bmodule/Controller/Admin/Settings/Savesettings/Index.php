<?php
namespace Opoink\Bmodule\Controller\Admin\Settings\Savesettings;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Savesettings';
	protected $_url;
	protected $_message;
	protected $_formValidate;
	protected $_settings;

	/**
	 * \Of\Std\Lang
	 */
	protected $_lang;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\FormValidate $FormValidate,
		\Opoink\Bmodule\Lib\Settings $Settings,
		\Opoink\Bmodule\Lib\Lang $Lang
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_formValidate = $FormValidate;
		$this->_settings = $Settings;
		$this->_lang = $Lang;
	}

	public function run(){
		
		$postFields = $this->getPost();
		$form_key = $this->getPost('form_key');
		$form_builder_form_name = $this->getPost('form_builder_form_name');

		$isValid = $this->_formValidate->validate($form_builder_form_name, $form_key);

		/**
		 * validate each field using _formValidate validateField method
		 * loop trough the post variables
		 */

		foreach ($postFields as $key => $field) {

			/**
			 * this post param is generated in form builder and renderer
			 * so we dont have to do anything with this here
			 */
			if($key == 'form_builder_form_name' || $key == 'form_builder_form_key' || $key == 'settings_key' || $key == 'save_settings'){
				continue;
			}


			$data = $this->_formValidate->validateField($key);
			if(is_array($data) && !$data["valid"]){
				$this->_message->setMessage($data["message"], 'danger');
			}
			else if(is_string($data)){
				$settingKeys = $this->getPost('settings_key') . $key . '/value';
				$save = $this->_settings->saveSettings($settingKeys, $field);
				if($save){
					$message = $this->_lang->_getLang('The {{input_field}} setting field successfully saved.', [
						[
							'key' => 'input_field', 
							'value' => ucwords(str_replace('_', ' ', $key))
						]
					]);
					$this->_message->setMessage($message, 'success');
				}
			}
		}

		$this->_url->redirect('/settings/allsettings', $this->getParam());

		return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
