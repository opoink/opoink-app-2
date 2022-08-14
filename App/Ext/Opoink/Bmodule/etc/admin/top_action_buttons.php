<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
return [
	'admin_adminuser_index_index' => [
		[
			'label' => 'Add New Admin User',
			'html_attr' => [
				"type" => "button",
				"id" => "add-new-admin-user",
				"class" => "btn btn-primary ms-2",
				"data-action" => 'adminuser/add',
				"data-target" => 'link',
			],
			'active' => true,
			'position' => 3
		],
		[
			'label' => 'Home',
			'html_attr' => [
				"type" => "button",
				"class" => "btn btn-outline-secondary ms-2",
				"data-action" => '',
				"data-target" => 'link',
			],
			'active' => true,
			'position' => 2
		]
	],
	'admin_adminuser_add_index' => [
		[
			'label' => 'Save New Admin',
			'html_attr' => [
				"type" => "button",
				"id" => "add-new-admin",
				"class" => "btn btn-primary ms-2",
				"data-action" => 'adminuser/save',
				"data-target" => 'form_submit',
				"data-target_form_id" => "form_1"
			],
			'active' => true,
			'position' => 2
		],
		[
			'label' => 'Back',
			'html_attr' => [
				"type" => "button",
				"class" => "btn btn-outline-secondary ms-2",
				"data-action" => 'adminuser',
				"data-target" => 'link',
			],
			'active' => true,
			'position' => 1
		]
	],
]
?>