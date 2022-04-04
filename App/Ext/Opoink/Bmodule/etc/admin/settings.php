<?php
/**
 * Copyright 2018 Opoink Framework (http://opoink.com/)
 * Licensed under MIT, see LICENSE.md
 * 
 * 
 * These are the default settings of the Opoink base module
 * you can see here how the form builder work
 * the 1st level is the tab
 * the 2nd level is the sub-tab
 * the 3rd level is the form group
 * the 4th level is the input field
 * 
 * the 4th level input field here is an array that is actually the option of the Opoink form builder.
 */
return [
	"general" => [
		"site" => [
			"site" => [
				"site_name" => [ 
					
					'label' => "Site Name",
					"attributes" => [ /** the value here the the actual attribute of the input field */
						"type" => "text", /** this is the actual input field type can be text, button, select, multiselect, radio, checkbod and textarea */
						"name" => "site_name", /** input field name */
						"placeholder" => "Site Name", /** input field placeholder for text and textarea */
						"class" => 'testing-class' /** html class will be appended into default class that is from bootstrap */
					],
					"comment" => [
						[ /** this is an array so that we can add more comments */
							"text" => 'testing lang muna', /** the field comment text */
							"class" => 'text-muted', /** will be appended into bootstrap form-text */
						]
					],
					"value" => "Testing", /** this is the default value of the field */
					"rules" => ["required"] /** field rules during */
				],
				"site_name_2" => [ 
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
	],
	"payment_methods" => [
		"paypal" => [
			"paypay_config" => [
				"email" => [ 
					'label' => "PayPayl Email",
					"attributes" => [
						"type" => "text",
						"name" => "paypal_email",
						"placeholder" => "PayPal Email"
					],
					"value" => "paypal@opoink.com",
					"rules" => ["required"]
				],
			]
		]
	]
];
?>