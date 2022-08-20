<?php
namespace Opoink\Bmodule\Controller\Admin\Grid\Export;

class Csvfile extends \Opoink\Bmodule\Controller\Admin\Uicomponents\Grid\Listing {

	const STATUS_PENDING = "pending";
	const STATUS_ACTIVE = "active";
	const STATUS_DONE = "done";

	protected $pageTitle = 'Grid Export Csvfile';

	/**
	 * \Opoink\Bmodule\Entity\GridListingExport
	 */
	protected $_entityGridListingExport;

	/**
	 * \Of\Std\Lang
	 */
	protected $_lang;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\GridBookmark $GridBookmark,
		\Opoink\Bmodule\Block\Admin\UiComponents\Grid $GridBlock,
		\Opoink\Bmodule\Lib\Lang $Lang,
		\Opoink\Bmodule\Entity\GridListingExport $GridListingExport
	){
		parent::__construct($Url, $Message, $Session, $GridBookmark, $GridBlock);
		$this->_entityGridListingExport = $GridListingExport;
		$this->_lang = $Lang;
	}

	public function run(){
		$this->_session->requireLoggedIn();
		
		$listingName = $this->getParam('listing_name');
		if(!empty($listingName)){
			$isExist = $this->_entityGridListingExport->getByColumn([
				'listing_name' => $listingName
			]);

			if(!$isExist){
				$this->_entityGridListingExport->setListingName($listingName)
				->setListingName($listingName)
				->setLastId(0)
				->setStatus(self::STATUS_PENDING)
				->setGeneratedFile(ROOT.DS.'modules'.DS.'admin_grid_export'.DS.$listingName.'.csv');
				
				$id = $this->_entityGridListingExport->save();
				if($id){
					$this->_message->setMessage($this->_lang->_getLang('Data is being generated. Please wait for a while to complete the file.'), 'success');
				}
				else {
					$this->_message->setMessage($this->_lang->_getLang('Cannot save the new export job for this listing, please try again.'), 'danger');
				}
			}
			else {
				$this->_message->setMessage($this->_lang->_getLang('The system is generating an active export for this listing.'), 'info');
			}
		}
		else {
			$this->_message->setMessage($this->_lang->_getLang('Invalid request'), 'danger');
		}

		$origin = $this->_request->getServer('HTTP_REFERER');
		if($origin){
			$this->_url->redirectTo($origin);
		}
		else {
			$this->_url->redirect('');
		}
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
