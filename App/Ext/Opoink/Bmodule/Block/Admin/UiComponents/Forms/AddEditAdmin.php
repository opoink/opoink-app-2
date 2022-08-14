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

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Block\Admin\ContentTopBottons $ContentTopBottons,
		\Opoink\Bmodule\Lib\FormBuilder $FormBuilder
		// \Opoink\Wh\Entity\Branches $Branches
	){
		parent::__construct($Url, $Config, $Lang);
		$this->contentTopBottons = $ContentTopBottons;
		$this->_formBuilder = $FormBuilder;
		// $this->_entityBranches = $Branches;
	}

	// protected function getBranch($branch_id){
	// 	$data = $this->_entityBranches->getByColumn([
	// 		'branch_id' => $branch_id
	// 	]);

	// 	if($data){
	// 		return $data;
	// 	}
	// 	else {
	// 		return $this->_entityBranches->setData($this->_controller->getParam());
	// 	}
	// }
}

?>