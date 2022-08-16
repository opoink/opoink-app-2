<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Entity;

class GridBookmark extends \Of\Database\Entity {

	const COLUMNS = [
		'grid_bookmark_id',
		'admins_id',
		'listing_name',
		'filters'
	];

	protected $tablename = "grid_bookmark";
	protected $primaryKey = "grid_bookmark_id";

    protected $_lang;

	/**
	 * \Opoink\Bmodule\Lib\Admin\UserSession
	 */
    protected $_userSession;
	
	public function __construct(
		\Of\Database\Connection $Connection,
		\Of\Http\Request $Request,
		\Opoink\Bmodule\Lib\Admin\UserSession $UserSession
	){
		parent::__construct($Connection, $Request);
		$this->_userSession = $UserSession;
	}

	/**
	 * try to fetch grid bookmark
	 * and save new bookmark for the current listing if no bookmark found
	 * @param $listingName
	 * @param $columns the default columns to be saved if no found in the database
	 */
	public function getAdminBookmark($listingName, $columns, $filters=null){
		$adminsId = $this->_userSession->getAdminUser()->getAdminsId();

		$bookMark = $this->getByColumn([
			'admins_id' => $adminsId,
			'listing_name' => $listingName,
		]);

		if(!$bookMark){
			$bmData = [
				'columns' => $columns,
				'filters' => [
					'limit' => \Opoink\Bmodule\Model\UiComponent\Grid::DEFAULT_LIMIT,
					'page' => 1,
					'search_fields' => []
				]
			];

			$this->setData([
				'admins_id' => $adminsId,
				'listing_name' => $listingName,
				'filters' => json_encode($bmData)
			]);

			$id = $this->save();
			$this->setGridBookmarkId($id);

			return $this->getAdminBookmark($listingName, $columns);
		}
		else {
			if($filters){
				$bmData = [
					'columns' => $columns,
					'filters' => $filters
				];
				$bookMark->setFilters(json_encode($bmData))->save();
			}

			$_filters = $bookMark->getFilters();
			$_filters = json_decode($_filters, true);
			$bookMark->setFilters($_filters);
			return $bookMark;
		}
	}

}
?>