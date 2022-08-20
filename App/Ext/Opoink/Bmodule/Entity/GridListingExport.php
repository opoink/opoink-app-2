<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Entity;

class GridListingExport extends \Of\Database\Entity {

	const COLUMNS = [
		'grid_listing_export_id',
		'listing_name',
		'last_id',
		'status',
		'generated_file',
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

}
?>