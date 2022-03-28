<?php
namespace Opoink\Bmodule\Entity;

class Admins extends \Of\Database\Entity {

	const COLUMNS = [
		'admins_id',
		'email',
		'username',
		'firstname',
		'firstname',
		'lastname',
		'invalid_login_count',
		'status',
		'lang',
		/** 'created_at', we dont need created_at here */
		/** 'upaded_at', we dont need upaded_at here */
	];

	protected $tablename = "admins";
	protected $primaryKey = "admins_id";

    protected $_lang;
	
	public function __construct(
		\Of\Database\Connection $Connection,
		\Of\Http\Request $Request
	){
		parent::__construct($Connection, $Request);
	}

}
?>