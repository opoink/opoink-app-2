<?php
namespace Opoink\Bmodule\Block\Admin\UiComponents\DataProvider;

class AdminUserStatus {

	public function toOptionArray(){
		$optionArray = [
			[
				"key" => 'Active',
				"value" => 1
			]
		];

		return $optionArray;
	}
}
?>