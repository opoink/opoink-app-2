<?php
namespace Opoink\Bmodule\Model\UiComponent\Grid;

class AdminAdminuserIndexIndexGrid extends \Opoink\Bmodule\Entity\Admins {

	protected $_eventManager;

	public function __construct(
		\Of\Database\Connection $Connection,
		\Of\Http\Request $Request,
		\Of\Std\EventManager $EventManager
	){
		parent::__construct($Connection, $Request);
		$this->_eventManager = $EventManager;
	}
	
	public function getList(){

		$mainTable = $this->getTableName();
		$select = $this->getSelect();
		$select->select()->from($mainTable);
		// $select->limit(10);

		$this->_eventManager->runEvent('product_grid_execute_db_query_before', [
			"select" => $select
		]);
	
		$data = $this->fetchAll($select, false);
		foreach ($data as $key => $value) {
			$DataObject = new \Of\Std\DataObject();
			$DataObject->setData($value);

			$data[$key] = $DataObject;
		}

		$this->_eventManager->runEvent('product_grid_execute_db_query_after', [
			"data" => $data
		]);

		return $data;
	}
}
?>