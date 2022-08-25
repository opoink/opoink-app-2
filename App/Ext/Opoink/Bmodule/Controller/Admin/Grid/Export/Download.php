<?php
namespace Opoink\Bmodule\Controller\Admin\Grid\Export;

class Download extends \Of\Controller\Controller {

	protected $pageTitle = 'Grid Export Download';
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
				if($entry->getStatus() == \Opoink\Bmodule\Controller\Admin\Grid\Export\Csvfile::STATUS_DONE){
					$targetFile = $entry->getGeneratedFile();
					$mime_content_type = mime_content_type($targetFile);
					
					$lifetime = 60*60*24*60;
					header('Content-Disposition: inline; filename="'.basename($targetFile).'"');
					header('Last-Modified: '. gmdate('D, d M Y H:i:s', filemtime($targetFile)) .' GMT');
					header('Expires: '. gmdate('D, d M Y H:i:s', time() + $lifetime) .' GMT');
					header('Pragma: ');
					header('Cache-Control: public, max-age='.$lifetime.', no-transform');
					header('Accept-Ranges: none');
					header('Content-type: ' . $mime_content_type);
					header('Content-Length: ' . filesize($targetFile));
					@readfile($targetFile);
					exit;
					die;
				}
				else {
					$this->_message->setMessage($this->_lang->_getLang('The system is generating an active export for this listing.'), 'info');
				}
			}
			else {
				$this->_message->setMessage($this->_lang->_getLang('Exported file nor found.'), 'danger');
			}
		}
		else {
			$this->_message->setMessage($this->_lang->_getLang('Exported file nor found.'), 'danger');
		}

		$this->_url->redirect('/adminuser');
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
