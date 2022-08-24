<?php
namespace Opoink\Bmodule\Controller\Admin\Grid\Export;

class Csvfiles extends \Of\Controller\Controller {

	protected $pageTitle = 'Grid Export Csvfiles';
	protected $_url;
	protected $_message;

	/**
	 * \Opoink\Bmodule\Lib\Admin\UserSession
	 */
	protected $_session;

	/**
	 * \Opoink\Bmodule\Entity\GridListingExport
	 */
	protected $_gridListingExport;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\GridListingExport $GridListingExport
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_session = $Session;
		$this->_gridListingExport = $GridListingExport;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		$listingName = $this->getParam('listing_name');

		if(!empty($listingName)){
			$AdminsId = $this->_session->getAdminUser()->getAdminsId();
			$data = $this->_gridListingExport->getExportsByAdminUserId($AdminsId, $listingName);
			
			if(count($data['data'])){
				foreach ($data['data'] as $key => &$value) {
					$fileName = pathinfo($value['generated_file'], PATHINFO_FILENAME);
					$ext = pathinfo($value['generated_file'], PATHINFO_EXTENSION);
					$value['generated_file'] = $fileName . '.' . $ext;
				}
			}

			$this->toJson($data);
		}
		else {
			$this->returnError(406, 'Invalid listing name');
		}
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
