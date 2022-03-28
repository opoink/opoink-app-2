<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Block\Admin;

class MainFooter extends \Opoink\Bmodule\Block\Admin\Context {
	
	public function __construct(
		\Of\Http\Url $Url,
		\Of\Config $Config,
		\Opoink\Bmodule\Lib\Lang $Lang
	){
		parent::__construct($Url, $Config, $Lang);
	}
}
?>