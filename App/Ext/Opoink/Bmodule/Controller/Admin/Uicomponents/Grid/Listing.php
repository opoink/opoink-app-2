<?php
namespace Opoink\Bmodule\Controller\Admin\Uicomponents\Grid;

class Listing extends \Of\Controller\Controller {

	protected $pageTitle = 'Uicomponents Grid Listing';
	protected $_url;
	protected $_message;

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	/**
	 * \Opoink\Bmodule\Entity\GridBookmark
	 */
	protected $gridBookmark;

	/**
	 * \Opoink\Bmodule\Block\Admin\UiComponents\Grid
	 */
	protected $gridBlock;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\GridBookmark $GridBookmark,
		\Opoink\Bmodule\Block\Admin\UiComponents\Grid $GridBlock
	){
		$this->_url = $Url;
		$this->_message = $Message;
		$this->_session = $Session;
		$this->gridBookmark = $GridBookmark;
		$this->gridBlock = $GridBlock;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		$listingName = $this->getParam('listing_name');

		if(!empty($listingName)){

			$this->gridBlock->setListingName($listingName);
			$this->gridBlock->collectAllListingArray();
	
			$columns = $this->gridBlock->getListingInfo()->getColumns();
			$limits = $this->gridBlock->getListingInfo()->getLimit();
			$topButtons = $this->gridBlock->getListingInfo()->getTopButtons();
			
			$colNames = [];
			foreach ($columns as $key => $value) {
				if(isset($value['db_table_column']) && $value['db_table_column'] == true){
					if(isset($value['column_name'])){
						$colNames[] = $value['column_name'];
					}
				}
			}

			$filters = $this->getParam('filters');
	
			$bookmark = $this->gridBookmark->getAdminBookmark($listingName, $columns, $filters);

			$filters = $bookmark->getFilters();

			unset($filters['columns']);
			$_GET['filters'] = $filters['filters'];

			$collectionModel = $this->gridBlock->getModel();

			$collectionModel = $this->_di->get($collectionModel);
			$collectionModel->setGridData($this->gridBlock->getListingInfo());
			$collectionModel->initSelect($colNames);

			$listData = $collectionModel->getList();

			

			foreach ($columns as $ckey => &$cvalue) {
				if(isset($cvalue['renderer']) && !empty($cvalue['renderer'])){
					$renderer = $this->_di->get($cvalue['renderer']);
					foreach ($listData['data'] as $key => &$value) {
						$value = $renderer->render($value, $cvalue['column_name']);
					}
					unset($cvalue['renderer']);
				}
				if(isset($cvalue['filter'])){
					if(isset($cvalue['filter']['type']) && $cvalue['filter']['type'] == 'select'){
						if(isset($cvalue['filter']['option'])){
							$option = $this->_di->get($cvalue['filter']['option'])->toOptionArray();
							$cvalue['filter']['option'] = $option;
						}
					}
				}
				if(array_key_exists('db_table_column', $value)){
					unset($cvalue['db_table_column']);
				}
			}

			$result = [
				'columns' => $columns,
				'list_data' => $listData,
				'filters' => $filters,
				'limits' => $limits,
				'top_buttons' => $topButtons
			];
	
			$this->toJson($result);
		}
		else {
			$this->returnError(406, 'Invalid listing name');
		}
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
