<?php
/**
 * Copyright 2022 Opoink Framework (http://opoink.com/)
 * Licensed under MIT, see LICENSE.md
 */
namespace Opoink\Bmodule\Cronjobs;

class AdminGridExportCsvfile {

	/**
	 * \Opoink\Bmodule\Entity\GridListingExport
	 */
	protected $_entityGridListingExport;

	/**
	 * \Of\Database\Entity
	 */
	protected $_entity;

	/**
	 * \Of\Std\DataObject
	 */
	protected $_dataObject;

	/**
	 * \Of\Std\Di
	 */
	protected $_di;

	/**
	 * \Of\File\Writer
	 */
	protected $fileWriter;

	public function __construct(
		\Opoink\Bmodule\Entity\GridListingExport $GridListingExport,
		\Of\File\Writer $FileWriter
	){
		$this->_entityGridListingExport = $GridListingExport;
		$this->fileWriter = $FileWriter;
		$this->_di = new \Of\Std\Di();
	}

	public function generateGridData(){
		$entries = $this->_entityGridListingExport->getExports();
		foreach ($entries as $entry) {
			$listingName = $entry['listing_name'];

			$targetListingFile = ROOT.DS.'Var'.DS.'modules'.DS.'admin_grid'.DS.$listingName.'.json';
			if(file_exists($targetListingFile) && is_file($targetListingFile)){
				$listing = file_get_contents($targetListingFile);
				$listing = json_decode($listing, true);

				if(json_last_error() == JSON_ERROR_NONE){
					$this->_dataObject = new \Of\Std\DataObject();
					// $mainTable = 'main_table';

					$this->_dataObject->setData($listing);
					$model = $this->getModel();

					$columns = $this->_dataObject->getColumns();
					
					$colNames = [];
					foreach ($columns as $key => $value) {
						if(isset($value['db_table_column']) && $value['db_table_column'] == true){
							if(isset($value['column_name'])){
								$colNames[] = $value['column_name'];
							}
						}
					}

					$entity = $this->_di->make('\Of\Database\Entity');
					$entity->setTableName('grid_bookmark');

					$filters = json_decode($entry['filters'], true);

					$collectionModel = $this->_di->get($model);
					$collectionModel->setGridData($this->_dataObject);
					$primaryKey = $collectionModel->getPrimaryKey();

					/** check if primary key range search exist */
					$isPKRangeSearchExist = false;
					foreach ($filters['filters']['search_fields'] as $key => $value) {
						if($value['field'] == $primaryKey && $value['type'] == 'range'){
							$isPKRangeSearchExist = true;
							break;
						}
					}
					if(!$isPKRangeSearchExist){
						if(empty($entry['export_id_from'])){
							$filters['filters']['limit'] = 1;
							$filters['filters']['page'] = 1;
	
							$_GET['filters'] = $filters['filters'];
	
							$collectionModel->initSelect($colNames);
							$data = $collectionModel->getList();
							if(isset($data['data']) && isset($data['data'][0])){
								$entry['export_id_from'] = $data['data'][0][$primaryKey];
							}
	
	
							$filters['filters']['page'] = $data['total_page'];
							$_GET['filters'] = $filters['filters'];
							$collectionModel->initSelect($colNames);
							$data = $collectionModel->getList();
							if(isset($data['data']) && is_array($data['data'])){
								$last = end($data['data']);
								$entry['export_id_to'] = $last[$primaryKey];
							}
						}

						$filters['filters']['search_fields'][] = [
							'type' => 'range',
							'from' => $entry['export_id_from'],
							'to' => $entry['export_id_to'],
							'field' => $primaryKey
						];
					}

					if($entry['status'] == \Opoink\Bmodule\Controller\Admin\Grid\Export\Csvfile::STATUS_PENDING){
						$filters['filters']['limit'] = \Opoink\Bmodule\Model\UiComponent\Grid::EXPORT_DEFAULT_LIMIT;
						$filters['filters']['page'] = 1;
						$entry['status'] = \Opoink\Bmodule\Controller\Admin\Grid\Export\Csvfile::STATUS_ACTIVE;
					}
					else {
						$filters['filters']['page'] = $filters['filters']['page'] + 1;
					}
					$entry['filters'] = json_encode($filters);
					
					$_GET['filters'] = $filters['filters'];
					$collectionModel->initSelect($colNames);
	
					$_data = $collectionModel->getList();

					$entry['total_count'] = $_data['total_count'];

					if($_data['total_page'] <= $filters['filters']['page']){
						$entry['status'] = \Opoink\Bmodule\Controller\Admin\Grid\Export\Csvfile::STATUS_DONE;
					}
					

					$data = $_data['data'];

					$dataCount = count($data);
					if($dataCount){

						$entry['current_count'] += $dataCount;

						$this->_entityGridListingExport->setData($entry)->save();

						$targetExportFile = $entry['generated_file'];

						$fileName = pathinfo($targetExportFile, PATHINFO_FILENAME);
						if(!file_exists($targetExportFile)){
							$this->fileWriter->setDirPath(dirname($targetExportFile))
							->setData('')
							->setFilename($fileName)
							->setFileextension('csv')
							->write();

							$fp = fopen($targetExportFile, 'a');
							fputcsv($fp, $colNames);
						}
						else {
							$fp = fopen($targetExportFile, 'a');
						}

						foreach ($data as $key => $value) {
							fputcsv($fp, $value);
						}
						fclose($fp);
					}
				}
			}

			$generatedFile = $entry['generated_file'];
		}
	}

		/**
	 * retrieve the model to be used from the merged JSON file
	 * the model will be the highest in priority
	 */
	public function getModel(){
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
		}
		else {
			$objectClasses = $this->_dataObject->getData('model/class');
			if(is_string($objectClasses)){
				return $objectClasses;
			}
		}
	}
}
?>