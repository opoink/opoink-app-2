<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin\UiComponents;

class Grid extends \Opoink\Bmodule\Block\Admin\Context {

	/**
	 * this is the listing name, serves as the filename as well
	 * if should be located at Vendor/Module/View/ui_component/grid/<listingName>.json file
	 */
	protected $listingName = '';

	protected $tableClass = 'table table-striped table-dark';

	/**
	 * \Of\Std\DataObjec
	 */
	protected $_dataObject = null; 

	/**
	 * 
	 */
	protected $collectionModel = null;

	/**
	 * \Opoink\Bmodule\Block\Admin\ContentTopBotton
	 */
	protected $contentTopBottons;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Block\Admin\ContentTopBottons $ContentTopBottons
	){
		parent::__construct($Url, $Config, $Lang);
		$this->_dataObject = new \Of\Std\DataObject();
		$this->getAllListingArray();
		$this->contentTopBottons = $ContentTopBottons;
	}

	/**
	 * retrieve the listing array in all installed modules
	 * and merge it as one, the array file must be in Vendor/Module/View/ui_component/grid/<listingName>.json file
	 * if the environment is set to prod, the listing array will only
	 * fetch for the cached listing
	 * to renew the cached it is need to purge the cached file in
	 * var/bmod/ui_component/grid dir
	 */
	protected function getAllListingArray(){
		if(!empty($this->listingName)){
			$vendors = $this->_config->getConfig('modules');
			$extDir = ROOT.DS.'App'.DS.'Ext'.DS;

			foreach ($vendors as $vendor => $modules) {
				foreach ($modules as $module) {
					$targetFile = $extDir . $vendor.DS.$module.DS.'View'.DS.'ui_component'.DS.'grid'.DS.$this->listingName.'.json';
					if(file_exists($targetFile) && is_file($targetFile)){
						$data = file_get_contents($targetFile);
						$data = json_decode($data, true);

						if(json_last_error() === JSON_ERROR_NONE){
							$this->_dataObject->setData(array_merge_recursive($this->_dataObject->getData(), $data));
						}
					}
				}
			}

			$this->setColumnPosition();
			$this->collectionModel = $this->getModel();
		}
	}

	/**
	 * sort the columns by position decalred in JSON object
	 * if the position is not declared then it will be sorted at the end
	 */
	protected function setColumnPosition(){
		$columns = $this->_dataObject->getData('columns');

		$sortedColumn = [];
		$sortedColumnNoPosition = [];
		foreach ($columns as $key => $column) {
			if(!isset($column['posistion'])){
				$sortedColumnNoPosition[] = $column;
			}
			else {
				$sortedColumn[$column['posistion']] = $column;
			}
		}
		ksort($sortedColumn);

		$noPositionNumber = array_key_last ( $sortedColumn ) + 1;
		foreach ($sortedColumnNoPosition as $key => $column) {
			$column['posistion'] = $noPositionNumber;
			$sortedColumn[$column['posistion']] = $column;
			$noPositionNumber++;
		}

		$this->_dataObject->setData('columns', $sortedColumn);
	}

	/**
	 * retrieve the model to be used from the merged JSON file
	 * the model will be the highest in priority
	 */
	protected function getModel(){

		$priorities = $this->_dataObject->getData('model/priority');

		if(is_array($priorities) && isset($priorities[0])){
			
			$objectClasseKey = 0;
			$objectClasseKeyValue = 0;
			foreach ($priorities as $key => $priority) {
				if($priority >= $objectClasseKeyValue){
					$objectClasseKeyValue = $priority;
					$objectClasseKey = $key;
				}
			}
			
			$objectClasses = $this->_dataObject->getData('model/class');
			if(is_array($objectClasses) && isset($objectClasses[$objectClasseKey])){
				return $objectClasses[$objectClasseKey];
			}
			else {
				throw new \Exception("Model is required please add model for your grid e.g. {\"priority\": 100,\"class\":\"Vendor\\Module\\Your\\Grid\\Model\\Class\"}", 1);
			}
		}
		else {
			$objectClasses = $this->_dataObject->getData('model/class');
			if(is_string($objectClasses)){
				return $objectClasses;
			}
			else {
				throw new \Exception("Model priority is required please add model for your grid e.g. {\"priority\": 100,\"class\":\"Vendor\\Module\\Your\\Grid\\Model\\Class\"}", 1);
			}
		}
	}
}
?>