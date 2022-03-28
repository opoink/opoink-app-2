<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Entity;

class Settings extends \Of\Database\Entity {

	const COLUMNS = [
		'settings_id',
		'key',
		'value'
		/** 'created_at', we dont need created_at here */
		/** 'upaded_at', we dont need upaded_at here */
	];

	protected $tablename = "settings";
	protected $primaryKey = "settings_id";

	protected $settings = [];


	public function __construct(
		\Of\Database\Connection $Connection,
		\Of\Http\Request $Request
	){
		parent::__construct($Connection, $Request);
	}

	/**
	 * return all saved settings in the database
	 */
	public function getAllSettings(){
		
	}

	/**
	 * check if the settings was already added the variable
	 * if not the fetch it from the database
	 * return the value from database if exist
	 * of return the default value passed in if not
	 * @param $key string 
	 * @param $default any 
	 */
	public function getSetting($key, $default=null){
		if(!array_key_exists($key, $this->settings)){
			$data = $this->getByColumn(['key' => $key]);
			if($data){
				$this->settings[$key] = $data->getData('value');
			}
		}

		if(array_key_exists($key, $this->settings)){
			return $this->settings[$key];
		}
		else {
			return $default;
		}
	}
}
?>