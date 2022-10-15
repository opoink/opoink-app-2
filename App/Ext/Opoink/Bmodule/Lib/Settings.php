<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Lib;

class Settings extends \Of\Std\DataObject {

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
	// protected $settings; 

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

		parent::__construct([]);

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
		if($this->data){
			return $this->data;
		}
		else {
			$this->data = [];

			$extDir = ROOT.DS.'App'.DS.'Ext'.DS;
			$vendors = $this->_config->getConfig('modules');

			foreach ($vendors as $vendor => $modules) {
				foreach ($modules as $module) {
					$targetFile = $extDir . $vendor.DS.$module.DS.'etc'.DS.'admin'.DS.'settings.php';
					if(file_exists($targetFile) && is_file($targetFile)){
						$moduleSetings = include($targetFile);
						$this->setData(array_replace_recursive($this->data, $moduleSetings));
					}
				}
			}
			return $this->data;
		}
	}

	/**
	 * get the settings value
	 * @param $keys string sample general/site/site/site_name/value
	 * @param $isGetInDatabase boolean to if true then the we'll try to get the 
	 * value from the daabase return the array or string
	 */
	public function getSettings($keys = '', $isGetInDatabase=true){
		if(empty($keys)){
			return $this->data;
		}
		else {
			if($isGetInDatabase){
				if(isset($this->settingValues[$keys])){
					return $this->settingValues[$keys];
				}
				else {
					$dbSetting = $this->_settingsEntity->getByColumn([
						'key' => $keys
					]);
					if($dbSetting){
						$this->settingValues[$keys] = $dbSetting->getValue();
						return $this->settingValues[$keys];
					}
					else {
						return $this->getData($keys);
					}
				}
			}
			else {
				return $this->getData($keys);
			}
		}
	}

	public function saveSettings($keys, $value){
		$data = $this->_settingsEntity->getByColumn([
			'key' => $keys
		]);

		if(strlen($value) >= 1){
			if($data){
				$data->unsCreatedAt();
				$data->unsUpadedAt();
				$data->unsKey();

				$id = $data->setValue($value)->save();
			}
			else {
				$id = $this->_settingsEntity
				->setValue($value)
				->setKey($keys)
				->save();
			}

			return $this->_settingsEntity->getByColumn([
				'settings_id' => $id
			]);
		}
		else {
			if($data){
				$data->_delete();
			}
		}
	}
}
?>
