<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin;

class ContentTopBottons {

	/**
	 * \Of\Config
	 */
	protected $_config;

	/**
	 * \Of\Route\Router
	 */
	protected $_router;

	public function __construct(
		\Of\Config $Config
	){
		$this->_config = $Config;
		$this->_router = new \Of\Route\Router($this->_config->getConfig());
	}

	public function buttonsToHtml(){
		$btns = $this->collectButtons();

		/** sort buttons */
		$sortedBtnWithPosition = [];
		$sortedBtnWithNoPosition = [];
		foreach ($btns as $key => $btn) {
			if(isset($btn['active']) && $btn['active'] == true){
				if(isset($btn['position'])){
					$sortedBtnWithPosition[$btn['position']] = $btn;
				}
				else {
					$sortedBtnWithNoPosition[] = $btn;
				}
			}
		}
		ksort($sortedBtnWithPosition);
		$noPositionNumber = array_key_last ( $sortedBtnWithPosition ) + 1;
		foreach ($sortedBtnWithNoPosition as $key => $value) {
			$value['position'] = $noPositionNumber;
			$sortedBtnWithPosition[$value['position']] = $value;
			$noPositionNumber++;
		}
		$btns = $sortedBtnWithPosition;

		$html = '';
		foreach ($btns as $key => $btn) {
			$attr = [];
			if(isset($btn['html_attr'])){
				foreach ($btn['html_attr'] as $key_attr => $val_attr) {
					$attr[] = $key_attr . '="'.$val_attr.'"';
				}
			}
			$attr = implode(' ', $attr);
			$html .= '<button '.$attr.'>'.$btn['label'].'</button>';
		}
		return $html;
	}


	/**
	 * retrieve all action buttons based on page name
	 * action button is located at vendor/module/etc/admin/top_action_buttons.php 
	 * of each installed module
	 * this methos will only look for all installed module
	 */
	public function collectButtons(){

		$vendors = $this->_config->getConfig('modules');

		$atnBtns = [];

		$dir = ROOT . DS . 'App'.DS.'Ext'.DS;
		foreach ($vendors as $vendor => $modules) {
			foreach ($modules as $module) {
				$moduleDir = $dir . $vendor . DS . $module;
				$etcDIr = $moduleDir . DS . 'etc' . DS . 'admin' . DS;
				$targetFile = $etcDIr . 'top_action_buttons.php';

				$pagename = $this->_router->getPageName(false);

				if(file_exists($targetFile) && is_file($targetFile) ){
					$actionButtons = include($targetFile);
					if(isset($actionButtons[$pagename])){
						$btns = $actionButtons[$pagename];

						foreach ($btns as $key => $value) {
							$atnBtns[] = $value;
						}
					}
				}
			}
		}

		return $atnBtns;
	}
}

?>