<?php
namespace Opoink\Bmodule\Controller\Admin\Grid\Export;

class Csvfile extends \Opoink\Bmodule\Controller\Admin\Uicomponents\Grid\Listing {

	protected $pageTitle = 'Grid Export Csvfile';
	protected $_url;
	protected $_message;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Std\Message $Message,
		\Opoink\Bmodule\Lib\Admin\UserSession $Session,
		\Opoink\Bmodule\Entity\GridBookmark $GridBookmark,
		\Opoink\Bmodule\Block\Admin\UiComponents\Grid $GridBlock
	){
		parent::__construct($Url, $Message, $Session, $GridBookmark, $GridBlock);
	}

	public function run(){

		echo "<pre>";
		print_r('asasd');
		die;
		return parent::run();
	}

	public function setLayout($router){
		$this->_url->setRouter($router);
		return parent::setLayout($router);
	}
}
?>
