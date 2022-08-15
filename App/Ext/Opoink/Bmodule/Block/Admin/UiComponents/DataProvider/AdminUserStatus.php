<?php
namespace Opoink\Bmodule\Block\Admin\UiComponents\DataProvider;

class AdminUserStatus {

	/**
	 * \Opoink\Bmodule\Lib\Settings
	 */
	protected $_settings;

	public function __construct(
		\Opoink\Bmodule\Lib\Settings $Settings
	){
		$this->_settings = $Settings;
	}

	public function getStatuses(){
		$statuses = $this->_settings->getSettings('general/users/admin_users/status/value');
		$statuses = json_decode($statuses, true);
		return $statuses;
	}
	
	public function toOptionArray(){
		$optionArray = $this->getStatuses()['user_status'];
		return $optionArray;
	}
}
?>