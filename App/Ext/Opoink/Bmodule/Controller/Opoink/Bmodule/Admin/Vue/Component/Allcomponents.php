<?php
namespace Opoink\Bmodule\Controller\Opoink\Bmodule\Admin\Vue\Component;

class Allcomponents extends \Of\Controller\Controller {

	protected $pageTitle = '';
	protected $_url;
	protected $_message;
	protected $_config;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Of\Config $Config
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_config = $Config;
	}

	public function run(){
		$vendors = $this->_config->getConfig('modules');

		$vCom = [];
		foreach ($vendors as $vendor => $modules) {
			foreach ($modules as $module) {
				$moduleDir = ROOT.DS.'App'.DS.'Ext'.DS.$vendor.DS.$module;
				$jsDir = $moduleDir . DS . 'View' . DS . 'js' . DS . 'admin' . DS . 'vue-components';

				if(is_dir($jsDir)){
					$cdir = scandir($jsDir); 
					foreach ($cdir as $key => $file) {
						if($file == '.' || $file == '..'){
							continue;
						}
						if(is_file($jsDir . DS . $file)){
							$file = pathinfo($file);
							$vCom[] = $file['filename'];
						}
					}
				}
			}
		}

		$this->toJson($vCom);
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
