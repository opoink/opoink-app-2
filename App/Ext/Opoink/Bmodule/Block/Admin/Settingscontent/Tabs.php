<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin\Settingscontent;

class Tabs extends \Opoink\Bmodule\Block\Admin\Context {
	

	/**
	 * \Opoink\Bmodule\Lib\Settings
	 */
	protected $_settings;

	/**
	 * \Opoink\Bmodule\Lib\FormBuilder
	 */
	protected $_formBuilder;

	protected $currentTab = '';
	protected $currentSubTab = '';

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Lib\Settings $Settings,
		\Opoink\Bmodule\Lib\FormBuilder $FormBuilder
	){
		parent::__construct($Url, $Config, $Lang);
		$this->_settings = $Settings;
		$this->_formBuilder = $FormBuilder;
	}

	protected function getFields(){
		$this->currentTab = $this->_controller->getParam('tab');
		$this->currentSubTab = $this->_controller->getParam('sub');

		$fields = null;
		if($this->currentTab && $this->currentSubTab){
			$fields = $this->_settings->getSettings($this->currentTab.'/'.$this->currentSubTab, false);
		}

		return $fields;
	}
}

?>