<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Lib;

class FormValidate {

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	public function __construct(
		\Of\Session\Session $Session
	){
		$this->_session = $Session;
	}

	/**
	 * check if the form is valid.
	 * the form should have form_key and if the form name does not exist
	 * in the session forms then it is invalid request
	 * return the field names with the rules set on it
	 */
	public function validate($formName, $formKey){
		$sesFormKey = $this->_session->getData('form_fields/'.$formName.'/form_key');
		$fields = $this->_session->getData('form_fields/'.$formName.'/fields');
		if($formKey === $sesFormKey){
			return $fields;
		}
	}
}
?>