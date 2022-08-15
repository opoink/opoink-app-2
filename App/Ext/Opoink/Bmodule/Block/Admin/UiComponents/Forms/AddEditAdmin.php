<?php
namespace Opoink\Bmodule\Block\Admin\UiComponents\Forms;

class AddEditAdmin extends \Opoink\Bmodule\Block\Admin\Context {


	/**
	 * \Opoink\Bmodule\Block\Admin\ContentTopBotton
	 */
	protected $contentTopBottons;
	
	/**
	 * \Opoink\Bmodule\Lib\FormBuilder
	 */
	protected $_formBuilder;

	/**
	 * \Opoink\Bmodule\Entity\Admins
	 */
	protected $_entityAdmins;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Block\Admin\ContentTopBottons $ContentTopBottons,
		\Opoink\Bmodule\Lib\FormBuilder $FormBuilder,
		\Opoink\Bmodule\Entity\Admins $EntityAdmins
	){
		parent::__construct($Url, $Config, $Lang);
		$this->contentTopBottons = $ContentTopBottons;
		$this->_formBuilder = $FormBuilder;
		$this->_entityAdmins = $EntityAdmins;
	}

	protected function getAdmin(){
		$data = $this->_entityAdmins->getByColumn([
			'admins_id' => $this->_controller->getParam('admins_id')
		]);

		if($data){
			return $data;
		}
		else {
			return $this->_entityAdmins->setData($this->_controller->getParam());
		}
	}
}

?>