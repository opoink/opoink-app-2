<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin\Settingscontent;

class Buttons extends \Opoink\Bmodule\Block\Admin\Context {

	/**
	 * \Opoink\Bmodule\Block\Admin\ContentTopBottons
	 */
	protected $_contentTopBottons;

	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Block\Admin\ContentTopBottons $ContentTopBottons
	){
		parent::__construct($Url, $Config);
		$this->_contentTopBottons = $ContentTopBottons;
	}

	/**
	 * return top button in html string
	 */
	public function buttonsToHtml(){
		return $this->_contentTopBottons->buttonsToHtml();
	}
}

?>