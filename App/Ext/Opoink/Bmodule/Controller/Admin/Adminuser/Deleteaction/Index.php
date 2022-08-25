<?php
namespace Opoink\Bmodule\Controller\Admin\Adminuser\Deleteaction;

class Index extends \Of\Controller\Controller {

	protected $pageTitle = 'Adminuser Deleteaction Index';
	protected $_url;
	protected $_message;

	/**
	 * \Opoink\Bmodule\Lib\Lang
	 */
	protected $_lang;

	/**
	 * \Opoink\Bmodule\Lib\Admin\UserSession
	 */
	protected $_session;

	/**
	 * \Opoink\Bmodule\Entity\Admins
	 */
	protected $_entityAdmins;

	/**
	 * \Of\Std\EventManager
	 */
	protected $_eventManager;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\Admins $EntityAdmins,
		\Of\Std\EventManager $EventManager
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_lang = $Lang;
		$this->_session = $Session;
		$this->_entityAdmins = $EntityAdmins;
		$this->_eventManager = $EventManager;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		$id = (int)$this->getParam('admins_id');

		$adminUser = $this->_entityAdmins->getByColumn(['admins_id' => $id]);

		if($adminUser){
			$this->_eventManager->runEvent('admin_user_delete_before', [
				'admin_user' => $adminUser
			]);

			$adminUser->_delete();

			$this->_eventManager->runEvent('admin_user_delete_after', [
				'admins_id' => $id
			]);

			$this->_message->setMessage($this->_lang->_getLang('Admin user permanently deleted'), 'success');
		}
		else {
			$this->_message->setMessage($this->_lang->_getLang('Admin user not found'), 'danger');
		}
		$this->_url->redirect('/adminuser');
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
