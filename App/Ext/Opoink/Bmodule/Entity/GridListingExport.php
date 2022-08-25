<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Entity;

class GridListingExport extends \Of\Database\Entity {

	const COLUMNS = [
		'grid_listing_export_id',
		'admins_id',
		'listing_name',
		'last_id',
		'status',
		'generated_file',
		'total_count',
		'current_count',
		'filters',
		'export_id_from',
		'export_id_to'
	];

	protected $tablename = "grid_listing_export";
	protected $primaryKey = "grid_listing_export_id";

    protected $_lang;
	
	public function __construct(
		\Of\Database\Connection $Connection,
		\Of\Http\Request $Request
	){
		parent::__construct($Connection, $Request);
	}

	public function getExports(){
		$mainTable = $this->getTableName();
		$select = $this->getSelect();
		$select->select()->from($mainTable)
		->where('status')->eq(\Opoink\Bmodule\Controller\Admin\Grid\Export\Csvfile::STATUS_PENDING)
		->orWhere('status')->eq(\Opoink\Bmodule\Controller\Admin\Grid\Export\Csvfile::STATUS_ACTIVE)
		->limit(50);

		$data = $this->fetchAll($select, false);

		return $data;
	}

	public function getExportsByAdminUserId($AdminsId, $listingName){
		$mainTable = $this->getTableName();
		$select = $this->getSelect();

		$select->select([
			'grid_listing_export_id',
			'status',
			'generated_file',
			'total_count',
			'current_count',
			'created_at',
			'upaded_at',
		])->from($mainTable)
		->where('admins_id')->eq($AdminsId)
		->where('listing_name')->eq($listingName)
		->orderBy('grid_listing_export_id', 'DESC');

		return $this->getFinalResponse($select);
	}
}
?>