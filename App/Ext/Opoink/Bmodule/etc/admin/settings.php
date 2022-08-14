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
							"text" => 'Site name', /** the field comment text */
							"class" => 'text-muted d-block', /** will be appended into bootstrap form-text */
						]
					],
					"value" => "Opoink", /** this is the default value of the field */
					"rules" => [ /** field rules for validation */
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
						 * same: case sensitive, comparison of two input values
						 * url: the value should be a valid url link
						 */
						[
							"type" => "required", 
							"message" => '' /** to overwrite the default message  */
						],
						// [
						// 	"type" => "regex", 
						// 	"pattern" => "/[^a-z_\-\s0-9]/i",
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
						// [
						// 	"type" => "min_length", 
						// 	"message" => '', /** to overwrite the default message  */
						// 	"length" => 3 /** if not set the minimum length will be 1 */
						// ],
						// [
						// 	"type" => "max_length", 
						// 	"message" => '', /** to overwrite the default message  */
						// 	"length" => 5 /** if not set the maximum length will be 1 */
						// ],
						// [
						// 	"type" => "alpha", 
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
						// [
						// 	"type" => "int", 
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
						// [
						// 	"type" => "decimal", 
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
						// [
						// 	"type" => "alphanum", 
						// 	"message" => '' /** to overwrite the default message  */,
						// ],
					],
					"row_style" => true /** define the template will be used */
				],
				// "site_name_confirm" => [ 
				// 	'label' => "Site Name Confirm",
				// 	"attributes" => [ /** the value here the the actual attribute of the input field */
				// 		"type" => "text", /** this is the actual input field type can be text, button, select, multiselect, radio, checkbox and textarea */
				// 		"name" => "site_name_confirm", /** input field name */
				// 		"placeholder" => "Site Name Confirm", /** input field placeholder for text and textarea */
				// 	],
				// 	"value" => "Site Name Confirm", /** this is the default value of the field */
				// 	"rules" => [ /** field rules for validation */
				// 		[
				// 			"type" => "same",
				// 			"reference_input" => "site_name", /** the input value to compare, required if the type is "same" */
				// 			"message" => '' /** to overwrite the default message  */
				// 		],
				// 	],
				// 	"row_style" => true /** define the template will be used */
				// ]
			],
			"e_mails" => [
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
							"type" => "email", 
							"message" => '' /** to overwrite the default message  */
						],[
							"type" => "required", 
							"message" => '' /** to overwrite the default message  */
						]
					],
					"row_style" => true /** define the template will be used */
				],
			],
			"social_media_links" => [
				"facebook_url" => [ 
					'label' => "Facebook URL",
					"attributes" => [ /** the value here the the actual attribute of the input field */
						"type" => "text", /** this is the actual input field type can be text, button, select, multiselect, radio, checkbox and textarea */
						"name" => "facebook_url", /** input field name */
						"placeholder" => "https://facebook.com", /** input field placeholder for text and textarea */
						"class" => '' /** html class will be appended into default class that is from bootstrap */
					],
					"value" => "", /** this is the default value of the field */
					"rules" => [ /** field rules for validation */
						[
							"type" => "url", 
							"message" => '' /** to overwrite the default message  */
						]
					],
					"row_style" => true /** define the template will be used */
				]
			]
		],
		'users' => [
			'frontend_users' => [
				'status' => [
					'label' => "Status",
					"attributes" => [
						"type" => "textarea",
						"name" => "status",
					],
					"value" => file_get_contents(__DIR__ . '/settings_default_values/general.users.frontend_users.status.json'),
					// "value" => __DIR__ . '/settings_default_values/general.users.frontend_users.status.json',
					"row_style" => true,
					"comment" => [
						[
							"text" => 'JSON Formated Data',
							"class" => 'text-muted d-block',
						]
					],
				]
			],
			'admin_users' => [
				'status' => [
					'label' => "Status",
					"attributes" => [
						"type" => "textarea",
						"name" => "status",
					],
					"value" => file_get_contents(__DIR__ . '/settings_default_values/general.users.admin_users.status.json'),
					// "value" => __DIR__ . '/settings_default_values/general.users.frontend_users.status.json',
					"row_style" => true,
					"comment" => [
						[
							"text" => 'JSON Formated Data',
							"class" => 'text-muted d-block',
						]
					],
				]
			]
		]
	],
	"sales" => [
		"payment_methods" => [
			"paypal" => [
				"paypal_email" => [ 
					'label' => "PayPal Email",
					"attributes" => [
						"type" => "text",
						"placeholder" => "youremail@paypal.com",
						"autocomplete" => "off"
					],
					"value" => "",
					"rules" => [
						[
							"type" => "email", 
							"message" => '' /** to overwrite the default message  */
						]
					]
				],
			]
		]
	]
];
?>