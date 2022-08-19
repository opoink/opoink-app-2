<?php
namespace Opoink\Bmodule\Model\UiComponent;

class Grid extends \Of\Database\Entity {

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
		\Of\Http\Request $Request
	){
		parent::__construct($Connection, $Request);
	}

	public function setGridData(\Of\Std\DataObject $gridData){
		$this->gridData = $gridData;
		return $this;
	}

	/**
	 * @param $colNames table column names
	 */
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

		$this->applyFilters($mt);
		return $this;
	}

	/**
	 * with this method will do the sql query for the filter 
	 * of the requested search field params
	 * 
	 * the filters/search_fields is requested via post request from admin ui grid page
	 * and it is being saved into the database inorder to bookmark the search.
	 * 
	 * @param $mt main table
	 */
	public function applyFilters($mt){
		$search_fields = $this->rebuildSearchField();
		if(count($search_fields)){
			foreach ($search_fields as $key => $search_field) {
				if($key == 0){
					$this->gridSelect->where($mt.'.'.$search_field['field']);
				}
				else {
					$this->gridSelect->orWhere($mt.'.'.$search_field['field']);
				}

				if($search_field['type'] == 'text'){
					$this->gridSelect->like('%'.$search_field['search_string'].'%');
				}
				elseif($search_field['type'] == 'range'){
					$from = isset($search_field['from']) ? (int)$search_field['from'] : 0;
					$to = isset($search_field['to']) ? (int)$search_field['to'] : 0;

					if($from > 0 && $to <= 0){
						$this->gridSelect->gtoe($from);
					}
					elseif($from > 0 && $to > 0){
						if($to < $from){
							$to = $from;
						}
						$this->gridSelect->between($from, $to);
					}
					elseif($from <= 0 && $to > 0){
						$this->gridSelect->ltoe($to);
					}
					else {
						/**
						 * do nothing
						 * $this->gridSelect->where || $this->gridSelect->orWhere will be ignored
						 * if we do nothing here
						 */
					}
				}
			}
		}
	}

	/**
	 * this will all invalid search field e.g search_string is empty
	 * or range from and to isi empty etc.
	 */
	public function rebuildSearchField(){
		$search_fields = $this->_request->getParam('filters/search_fields');

		$newSearchFields = [];
		foreach ($search_fields as $key => $value) {
			/** the field index is required */
			if( !isset($value['field']) || empty($value['field']) ){ 
				continue;
			}

			if($value['type'] == 'text'){
				if( isset($value['search_string']) && !empty($value['search_string']) ){
					$newSearchFields[] = $value;
				}
			}
			elseif($value['type'] == 'range'){
				$from = isset($value['from']) ? (int)$value['from'] : 0;
				$to = isset($value['to']) ? (int)$value['to'] : 0;
				if(!empty($from) || !empty($to)){
					$newSearchFields[] = $value;
				}
			}
		}
		return $newSearchFields;
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

		$this->setOrderBy();


        $pagination = $this->getPagination();
        $pagination->set($page, $count, $limit);

		if($pagination->currentPage() > $pagination->total_pages()){
			$pagination->set($pagination->total_pages(), $count, $limit);
		}

        $this->gridSelect->offset($pagination->offset())
        ->limit($limit);

        $data = $this->fetchAll($this->gridSelect, false);

        $o = [
            'total_count' => $count,
            'total_page' => $pagination->total_pages(),
            'current_page' => $pagination->currentPage(),
            'per_page' => $this->gridSelect->_limit,
            'data' => $data
        ];
		
        return $o;
	}

	public function setOrderBy(){
		$order_by = $this->_request->getParam('filters/sort_order/order_by');
		if($order_by){
			$direction = $this->_request->getParam('filters/sort_order/direction');
			if($direction){
				$direction = strtolower($direction);
				if($direction == 'asc'){
					$direction = 'ASC';
				}
				elseif($direction == 'desc'){
					$direction = 'DESC';
				}
				else {
					$direction = 'ASC';
				}
			}
			else {
				$direction = 'ASC';
			}
			$this->gridSelect->orderBy($order_by, $direction);
		}
	}
}
?>