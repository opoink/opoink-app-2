<?php
namespace Opoink\Bmodule\Model\UiComponent;

class Grid extends \Of\Database\Entity {

	protected $_eventManager;

	/**
	 * \Of\Std\DataObject 
	 */
	protected $gridData;

	/**
	 * Of\Database\Sql\Select
	 */
	protected $gridSelect;

	/**
	 * this data limits will be used if the limitations are not set in the json data of grid
	 */
	protected $defaultLimits = [20,50,100];

	/**
	 * this limit will be used in case of the limit is not 
	 * in the set accepted limitations
	 */
	const DEFAULT_LIMIT = 20;

	public function __construct(
		\Of\Database\Connection $Connection,
		\Of\Http\Request $Request,
		\Of\Std\EventManager $EventManager
	){
		parent::__construct($Connection, $Request);
		$this->_eventManager = $EventManager;
	}

	public function setGridData(\Of\Std\DataObject $gridData){
		$this->gridData = $gridData;
		return $this;
	}

	public function initSelect($colNames=null){
		$mt = $this->getTableName($this->gridData->getMainTable());
		$this->gridSelect = $this->getSelect();

		$newColNames = [];
		foreach ($colNames as $key => $value) {
			$newColNames[$mt.'.'.$value] = $value;
		}

		$this->gridSelect->select($newColNames)->from([
			$mt => $mt
		]);

		$this->_eventManager->runEvent('admin_grid_init_select_after', [
			"select" => $this->gridSelect
		]);
	}

	public function getList(){
		$count = (int)$this->count($this->gridSelect, null, 'count');

		$page = (int)$this->_request->getParam('filters/page');
		if(!$page){
			$page = 1;
		}

		$limit = (int)$this->_request->getParam('filters/limit');
		if($limit){
			$limits = $this->gridData->getLimit();
			if(!$limits){
				$limits = $this->defaultLimits;
			}
			if(!in_array($limit, $limits)){
				$limit = self::DEFAULT_LIMIT;
			}
		}
		else {
			$limit = self::DEFAULT_LIMIT;
		}

        $pagination = $this->getPagination();
        $pagination->set($page, $count, $limit);

		if($pagination->currentPage() > $pagination->total_pages()){
			$pagination->set($pagination->total_pages(), $count, $limit);
		}

        $this->gridSelect->offset($pagination->offset())
        ->limit($limit);

        $data = $this->fetchAll($this->gridSelect, false);
		// foreach ($data as $key => $value) {
		// 	$DataObject = new \Of\Std\DataObject();
		// 	$DataObject->setData($value);

		// 	$data[$key] = $DataObject;
		// }

        $o = [
            'total_count' => $count,
            'total_page' => $pagination->total_pages(),
            'current_page' => $pagination->currentPage(),
            'per_page' => $this->gridSelect->_limit,
            'data' => $data
        ];
		
        return $o;
	}
}
?>