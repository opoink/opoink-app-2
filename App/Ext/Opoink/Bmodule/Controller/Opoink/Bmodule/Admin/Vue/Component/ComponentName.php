<?php
namespace Opoink\Bmodule\Controller\Opoink\Bmodule\Admin\Vue\Component;

class ComponentName extends \Of\Controller\Controller {

	protected $pageTitle = '';
	protected $_url;
	protected $_message;
	protected $_config;
	protected $_writer;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Of\Config $Config,
		\Of\File\Writer $Writer
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_config = $Config;
		$this->_writer = $Writer;
	}

	public function run(){
		$componentname = $this->getParam('componentname');

		$mode = $this->_config->getConfig('mode');

		$cacheDir = ROOT.DS.'Var'.DS.'Opoink_Bmodule'.DS.'vue-components';
		$cacheFile = $cacheDir.DS.$componentname;

		$js = null;
		if($mode == 'production'){
			$cacheDir = ROOT.DS.'Var'.DS.'Opoink_Bmodule'.DS.'vue-components';
			$cacheFile = $cacheDir.DS.$componentname;
			if(file_exists($cacheFile)){
				$js = file_get_contents($cacheFile);
			}
			else {
				$js = $this->getVueJsComponent($componentname);
				$path_parts = pathinfo($componentname);

				$this->_writer->setDirPath($cacheDir)
				->setData($js)
				->setFilename($path_parts['filename'])
				->setFileextension('js')
				->write();
			}
		}
		else {
			$js = $this->getVueJsComponent($componentname);
		}

		if($js){
			if(file_exists($cacheFile)){
				unlink($cacheFile);
			}
			echo header("Content-type: application/javascript", true);
			echo $js;
			exit;
			die;
		}
		else {
			$this->returnError(404, 'Page not found');
		}
	}

	protected function getVueJsComponent($componentname){
		$vendors = $this->_config->getConfig('modules');

		$js = null;
		foreach ($vendors as $vendor => $modules) {
			foreach ($modules as $module) {
				$moduleDir = ROOT.DS.'App'.DS.'Ext'.DS.$vendor.DS.$module;
				$jsDir = $moduleDir . DS . 'View' . DS . 'js' . DS . 'admin' . DS . 'vue-components';
				$tplDir = $moduleDir . DS . 'View' . DS . 'Template' . DS . 'admin' . DS . 'vue-components';

				$targetJs = $jsDir . DS . $componentname;

				if(file_exists($targetJs) && is_file($targetJs)){
					$js = file_get_contents($targetJs);

					$path_parts = pathinfo($componentname);

					$targetTpl = $tplDir . DS . $path_parts['filename'] . '.html';

					if(file_exists($targetTpl) && is_file($targetTpl)){
						$js = str_replace('{{template}}', file_get_contents($targetTpl), $js);
					}
					else {
						$js = str_replace('{{template}}', '<p>Template not found: '.$targetTpl.'</p>', $js);
					}
				}
			}
		}

		return $js;
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
