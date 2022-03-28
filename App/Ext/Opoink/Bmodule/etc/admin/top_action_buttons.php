<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
return [
	'admin_settings_allsettings_index' => [
		[
			'label' => 'save',
			'html_attr' => [
				"type" => "button",
				"id" => "action-save",
				"class" => "btn btn-primary",
				"data-action" => '/settings/saveconfig',
				"data-isajax" => '1',
				"data-target_form_id" => 'setting_form_fields',
			],
			'active' => true,
			'position' => 1
		]
	]
]
?>