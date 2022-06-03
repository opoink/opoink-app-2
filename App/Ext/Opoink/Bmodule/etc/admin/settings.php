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
						"type" => "text", /** this is the actual input field type can be text, button, select, multiselect, radio, checkbox and textarea */
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
					"rules" => [ /** field rules for validation */
						[
							/** 
							 * required: the field should have atleast 1 character long
							 * regex: regular expression preg_match will be used to compare
							 * email: must be a valid email address
							 * min_length: field should be in minimum cahracter long
							 * max_length: field should be in maximum cahracter long
							 * alpha: should conatin alpha chracters only
							 * int: should be a valid whole number
							 * decimal: should be a valid decimal number
							 * alphanum: alpha numeric character only
							 */
							"type" => "required", 
							"message" => '' /** to overwrite the default message  */
						],
						[
							"type" => "decimal", 
							"message" => '' /** to overwrite the default message  */,
						],
						// [
						// 	"type" => "int", 
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
						// [
						// 	"type" => "alpha", 
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
						// [
						// 	"type" => "regex", 
						// 	"pattern" => "/[^a-z_\-\s0-9]/i",
						// 	"message" => '' /** to overwrite the default message  */,
						// ]
					],
					"row_style" => true /** define the template will be used */
				],
			],
			"E-Mails" => [
				"contact_email" => [ 
					'label' => "Contact Us Email",
					"attributes" => [ /** the value here the the actual attribute of the input field */
						"type" => "text", /** this is the actual input field type can be text, button, select, multiselect, radio, checkbox and textarea */
						"name" => "contact_email", /** input field name */
						"placeholder" => "contactus@opoink.com", /** input field placeholder for text and textarea */
						"class" => '' /** html class will be appended into default class that is from bootstrap */
					],
					"value" => "", /** this is the default value of the field */
					"rules" => [ /** field rules for validation */
						[
							"type" => "required", 
							"message" => '' /** to overwrite the default message  */
						],
						[
							"type" => "email", 
							"message" => '' /** to overwrite the default message  */
						]
					],
					"row_style" => false /** define the template will be used */
				],
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