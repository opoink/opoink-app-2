<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Controller\Admin\Adminuser\Save;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Adminuser Save Index';
	protected $_url;
	protected $_message;

	/**
	 * \Opoink\Bmodule\Lib\Admin\UserSession
	 */
	protected $_session;

	/**
	 * \Opoink\Bmodule\Lib\FormValidate
	 */
	protected $_formValidate;

	/**
	 * \Opoink\Bmodule\Lib\Lang
	 */
	protected $_lang;

	/**
	 * \Opoink\Bmodule\Entity\Admins
	 */
	protected $_entityAdmins;

	/**
	 * \Of\Std\Password
	 */
	protected $_password;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\FormValidate $FormValidate,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\Admins $EntityAdmins,
		\Of\Std\Password $Password
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_formValidate = $FormValidate;
		$this->_session = $Session;
		$this->_lang = $Lang;
		$this->_entityAdmins = $EntityAdmins;
		$this->_password = $Password;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		$postFields = $this->getPost();
		$form_key = $this->getPost('form_builder_form_key');
		$form_builder_form_name = $this->getPost('form_builder_form_name');
		$id = (int)$this->getPost('admins_id');

		$returnErrorUrl = '/adminuser/add';
		if($id){
			$returnErrorUrl = '/adminuser/edit';
		}
		/**
		 * check the request if valid
		 */
		$isValid = $this->_formValidate->validate($form_builder_form_name, $form_key);

		if(is_array($isValid)){

			foreach ($postFields as $key => $postField) {
				/**
				 * this post param is generated in form builder and renderer
				 * so we dont have to do anything with this here
				 */
				if($key == 'form_builder_form_name' || $key == 'form_builder_form_key'){
					/**
					 * unset the these params for later use of the post fields
					 */
					unset($postFields[$key]);
					continue;
				}
				$data = $this->_formValidate->validateField($key);
				if(is_array($data) && !$data["valid"]){
					$this->_message->setMessage($data["message"], 'danger');

					/**
					 * have to redirect the add/edit admin user page because of an invalid
					 * input data
					 */
					$this->_url->redirect($returnErrorUrl, $postFields);
				}
			}

			$canSave = false;
			/**
			 * since the code gets here, we are sure that the request is valid
			 * and the field is all valid as well
			 * we can now check for the email and username existence
			 */
			$isExistEmail = $this->_entityAdmins->getByColumn(['email' => $postFields['email']]);

			if(!$isExistEmail){
				$isExistUsername = $this->_entityAdmins->getByColumn(['username' => $postFields['username']]);
				if(!$isExistUsername){
					$canSave = true;
				}
				else {
					if($isExistUsername->getAdminsId() == $id){
						$canSave = true;
					}
					else {
						$this->setExistMessage('username', $postFields['username']);
					}
				}
			}
			else {
				if($isExistEmail->getAdminsId() == $id){
					if($isExistEmail->getUsername() == $postFields['username']){
						$canSave = true;
					}
					else {
						$isExistUsername = $this->_entityAdmins->getByColumn(['username' => $postFields['username']]);
						if(!$isExistUsername){
							$canSave = true;
						}
						else {
							$this->setExistMessage('username', $postFields['username']);
						}
					}
				}
				else {
					$this->setExistMessage('email', $postFields['email']);
				}
			}

			if($canSave){
				if(!$id && isset($postFields['admins_id'])){
					unset($postFields['admins_id']); /** we have to unset the id because the request is saving for new data */
				}

				if(isset($postFields['password']) && empty($postFields['password'])){
					unset($postFields['password']); /** the password is not gonna be changed */
				}
				else {
					$postFields['password'] = $this->_password->setPassword($postFields['password'])->getHash();
				}

				$this->_entityAdmins->setData($postFields);
				$id = $this->_entityAdmins->save();

				$this->_message->setMessage($this->_lang->_getLang('Admin {{username}} successfully saved.', [
					[
						'key' => 'username', 
						'value' => $postFields['username']
					]
				]), 'success');
				$this->_url->redirect('/adminuser/edit', ['admins_id' => $id]);
			}
		}
		else {
			$this->_message->setMessage($this->_lang->_getLang('Invalid request'), 'danger');
		}

		$this->_url->redirect($returnErrorUrl, $postFields);
	}

	protected function setExistMessage($field, $value){
		$this->_message->setMessage($this->_lang->_getLang('The {{field}} {{value}} already exists, please choose another one.', [
			[
				'key' => 'field', 
				'value' => $field
			],
			[
				'key' => 'value', 
				'value' => $value
			]
		]), 'danger');
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
