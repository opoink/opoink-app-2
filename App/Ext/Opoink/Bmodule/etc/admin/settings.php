<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
return [
	"general" => [
		"site" => [
			"site" => [
				"site_name" => [ 
					/** the value here the the actual attribute of the input field */
					'label' => "Site Name",
					"attributes" => [
						"type" => "text",
						"name" => "site_name",
						"placeholder" => "Site Name"
					],
					"comment" => [
						'testing lang muna'
					],
					"value" => "Testing"
				],
				"site_name_2" => [ 
					/** the value here the the actual attribute of the input field */
					'label' => "Site Name 2",
					"attributes" => [
						"type" => "text",
						"name" => "site_name_2",
						"placeholder" => "Site Name 2",
					]
				]
			],
			"site_2" => [
				"site_name" => [ 
					/** the value here the the actual attribute of the input field */
					'label' => "Site 2",
					"attributes" => [
						"type" => "text",
						"name" => "site_2",
						"placeholder" => "Site 2",
					]
				]
			]
		]
	]
];
?>