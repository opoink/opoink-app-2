<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Lib;

class Lang extends \Of\Std\Lang {

	/**
	 * selected language default is en
	 */
	protected $localLang = 'en';

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	public function __construct(
		\Of\Config $Config,
		\Of\Session\Session $Session
	){
		parent::__construct($Config);
		$this->setLanguages();

		$this->_session = $Session;
		$this->_setLang();
	}

	public function saveLangToSession($lang){
		if(isset($this->languages[$lang])){
			$this->_session->setData('localization_selected_language', $lang);
		}
		else {
			$this->_session->unsetData('localization_selected_language');
		}
		return $this->_session->getData('localization_selected_language');
	}

	/**
	 * the language is saved at the admin user session
	 * but it is being saved when the user login
	 * and or when selecting language
	 */
	public function _setLang(){
		$lang = $this->_session->getData('localization_selected_language');
		if($lang){
			$this->localLang = $lang;
		}
	}

	/**
	 * return string for the language
	 * @param $lang string the text that will be fetched in the language JSON file
	 * @param $values array, if the language has variable text inside
	 */
	public function _getLang($lang, $values=null){
		return $this->getLang($lang, $this->localLang, $values);
	}

	/**
	 * retrieve all available langauges in the site
	 * return array 
	 */
	public function getAvailableLangauges(){
		$languages = [];
		foreach ($this->languages as $key => $value) {
			$languages[] = $key;
		}
		return $languages;
	}
}
?>