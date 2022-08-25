<?php
namespace Opoink\Bmodule\Controller\Admin\Grid\Export;

class Deletegeneratedfile extends \Of\Controller\Controller {

	protected $pageTitle = 'Grid Export Deletegeneratedfile';
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

	/**
	 * \Opoink\Bmodule\Lib\Lang
	 */
	protected $_lang;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\GridListingExport $GridListingExport,
		\Opoink\Bmodule\Lib\Lang $Lang
	){

		$this->_url = $Url;
		$this->_message = $Message;
		$this->_session = $Session;
		$this->_gridListingExport = $GridListingExport;
		$this->_lang = $Lang;
	}

	public function run(){
		$this->_session->requireLoggedIn();

		$id = (int)$this->getParam('export_id');

		$entry = $this->_gridListingExport->getByColumn([
			'grid_listing_export_id' => $id
		]);

		if($entry){
			$adminsId = $this->_session->getAdminUser()->getAdminsId();
			if($adminsId == $entry->getAdminsId()){
				if(file_exists($entry->getgenerated_file())){
					unlink($entry->getgenerated_file());
				}

				$entry->_delete();

				$this->toJson([
					'message' => $this->_lang->_getLang('Generated file successfully deleted.')
				]);
			}
			else {
				$this->returnError(406, $this->_lang->_getLang('Exported file nor found.'));
			}
		}
		else {
			$this->returnError(406, $this->_lang->_getLang('Exported file nor found.'));
		}
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
