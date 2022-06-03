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

	/**
	 * \Of\Http\Request
	 */
	protected $_request;

	/**
	 * \Of\Std\DataObject
	 */
	protected $_dataObject;

	/**
	 * \Of\Std\Lang
	 */
	protected $_lang;

	public function __construct(
		\Of\Session\Session $Session,
		\Of\Http\Request $Request,
		\Of\Std\DataObject $DataObject,
		\Opoink\Bmodule\Lib\Lang $Lang
	){
		$this->_session = $Session;
		$this->_request = $Request;
		$this->_dataObject = $DataObject;
		$this->_lang = $Lang;
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

	/**
	 * specifically validate the field using the given requirements
	 * 
	 * requirements can be 
	 * 	required: the field should have atleast 1 character long
	 * 	regex: regular expression preg_match will be used to compare
	 * 	email: must be a valid email address
	 * 	min_length: field should be in minimum cahracter long
	 * 	max_length: field should be in maximum cahracter long
	 * 	alpha: should conatin alpha chracters only
	 * 	int: should be a valid whole number
	 * 	decimal: should be a valid decimal number
	 * 	alphanum: alpha numeric character only
	 * 	TODO: 
	 * 		same
	 * 		url
	 * @param $postField this is the field name comming from the request
	 * return the value from the post if valid, return null if not
	 */
	public function validateField($postField, $rules=null){
		$value = $this->_request->getPost($postField);
		if($value === null){
			$value = '';
		}
		$value = trim($value);

		$formName = $this->_request->getPost('form_builder_form_name');
		if(!$rules){
			$rules = $this->_session->getData('form_fields/'.$formName.'/fields/'.$postField);
		}
		if(is_array($rules)){
			foreach ($rules as $key => $rule) {
				if(isset($rule['type'])){
					$rule['type'] = strtolower($rule['type']);

					if($rule['type'] == 'required'){
						if(strlen($value) <= 0){
							$value = $this->invalidFieldValue($postField, '{{field_name}} is required.', $rule, 'field_name');
							break;
						}
					}
					else if($rule['type'] == 'email'){
						if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
							$value = $this->invalidFieldValue($postField, '{{email}} is not a valid email address.', $rule, 'email');
							break;
						}
					}
					else if($rule['type'] == 'min_length' || $rule['type'] == 'max_length'){
						$length = 1;
						if(isset($rule['length'])){
							$length = (int)$rule['length'];
						}
						if(!$length){
							$length = 1;
						}
						$langVars = [
							['key' => '', 'value' => $this->_dataObject->camelCaseToSpace($postField, 'ucwords')],
							['key' => 'length', 'value' => $length],
						];
						if($rule['type'] == 'min_length' && strlen($value) < $length){
							$langVars[0]['key'] = 'min_length';
							$value = $this->invalidFieldValue($postField, 'The {{min_length}} must be a minimum of {{length}} characters long.', $rule, '', $langVars);
							break;
						}
						else if($rule['type'] == 'max_length' && strlen($value) > $length){
							$langVars[0]['key'] = 'max_length';
							$value = $this->invalidFieldValue($postField, 'The {{max_length}} must be a maximum of {{length}} characters long.', $rule, '', $langVars);
							break;
						}
					}
					else if($rule['type'] == 'regex'){
						if(isset($rule['pattern'])){
							if (preg_match($rule['pattern'], $value)) {
								$langVars = [
									['key' => 'value', 'value' => $value],
									['key' => 'field_name', 'value' => $this->_dataObject->camelCaseToSpace($postField, 'ucwords')],
								];
								$value = $this->invalidFieldValue($postField, 'The ({{value}}) is not valid to be a value of the {{field_name}}.', $rule, '', $langVars);
								break;
							}
						}
						else {
							$value = $this->invalidFieldValue($postField, 'The pattern for the {{regex}} field is not set.', $rule, 'regex');
							break;
						}
					}
					else if($rule['type'] == 'alphanum'){
						if (!ctype_alnum($value)) {
							$value = $this->invalidFieldValue($postField, 'The {{alphanum}} must contain alphanumeric characters only.', $rule, 'alphanum');
							break;
						}
					}
					else if($rule['type'] == 'alpha'){
						if (preg_match("/[^a-z]/i", $value)) {
							$value = $this->invalidFieldValue($postField, 'The {{alpha}} must contain alpha characters only.', $rule, 'alpha');
							break;
						}
					}
					else if($rule['type'] == 'int'){
						if (preg_match("/[^0-9]/i", $value)) {
							$value = $this->invalidFieldValue($postField, 'The {{int}} must contain numeric characters only.', $rule, 'int');
							break;
						}
					}
					else if($rule['type'] == 'decimal'){
						if (preg_match("/[^0-9\.]/i", $value)) {
							$value = $this->invalidFieldValue($postField, 'The {{decimal}} must contain a whole number or decimal numbers characters only.', $rule, 'decimal');
							break;
						}
					}
					else {
						/** do nothing here */
					}
				}
			}
		}

		return $value;
	}

	protected function invalidFieldValue($postField, $defaultMessage, $rule, $strKey='', $langVars=null){
		if(!$langVars){
			$langVars = [
				[
					'key' => $strKey, 
					'value' => $this->_dataObject->camelCaseToSpace($postField, 'ucwords')
				]
			];
		}

		$value = [
			'valid' => false,
			'message' => $this->_lang->_getLang($defaultMessage, $langVars)
		];
		if(isset($rule['message']) && !empty($rule['message'])){
			$value['message'] = $rule['message'];
		}
		return $value;
	}
}
?>