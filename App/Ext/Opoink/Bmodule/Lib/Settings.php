<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Lib;

class Settings {

	/**
	 * \Opoink\Bmodule\Entity\Settings
	 */
	protected $_settingsEntity;

	/**
	 * \Of\Config
	 */
	protected $_config;

	/**
	 * this variable will hold the values of the settings of all module
	 * in this case this class wont be needing to merge all settings again ang again
	 */
	protected $settings; 

	/**
	 * in this variable we will store all setting value 
	 * that came from the database
	 * so that it will be called once per page load
	 */
	protected $settingValues = [];

	public function __construct(
		\Of\Config $Config,
		\Opoink\Bmodule\Entity\Settings $Settings
	){
		$this->_settingsEntity = $Settings;
		$this->_config = $Config;

		$this->mergeAllModuleSetting();
	}

	/**
	 * merge all settings array from all installed module
	 * this use the array_replace_recursive meanings that is the 
	 * value from other module already exist
	 * then it wil be replaces by the value from the 
	 * currently scanned module setting
	 * return array it is the merger settings from different modules
	 * 
	 * notice that we return the settings if it is already merged
	 * since we are using PHP DI this methos will only fire once every page load 
	 * and if there was a function who called this class
	 */
	public function mergeAllModuleSetting(){
		if($this->settings){
			return $this->settings;
		}
		else {
			$this->settings = [];

			$targetFile = ROOT.DS.'App'.DS.'Ext'.DS;
			$vendors = $this->_config->getConfig('modules');

			foreach ($vendors as $vendor => $modules) {
				foreach ($modules as $module) {
					$targetFile .= $vendor.DS.$module.DS.'etc'.DS.'admin'.DS.'settings.php';
					if(file_exists($targetFile) && is_file($targetFile)){
						$moduleSetings = include($targetFile);
						$this->settings = array_replace_recursive($this->settings, $moduleSetings);
					}
				}
			}
			return $this->settings;
		}

	}

	/**
	 * get the settings value
	 * @param $keys string sample general/site/site/site_name/value
	 * @param $isGetInDatabase boolean to if true then the we'll try to get the 
	 * value from the daabase return the array or string
	 */
	public function getSettings($keys, $isGetInDatabase=true){
		if($isGetInDatabase){
			if(isset($this->settingValues[$keys])){
				return $this->settingValues[$keys];
			}
			else {
				$dbSetting = $this->_settingsEntity->getByColumn([
					'key' => $keys
				]);
				if($dbSetting){
					$this->settingValues[$keys] = $dbSetting->getData('value');
					return $this->settingValues[$keys];
				}
				else {
					return opoinkGetArrayValue($keys, $this->settings);
				}
			}
		}
		else {
			return opoinkGetArrayValue($keys, $this->settings);
		}
	}
}
?>