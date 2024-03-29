<?php
/**
* Copyright 2022 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Bmodule\Lib;

class FormBuilder {

	/**
	 * \Of\Session\Session
	 */
	protected $_session;

	/**
	 * \Of\Std\Password
	 */
	protected $_password;


	/**
	 * the value of this field is a form field html string 
	 * it is a bootsrap 5 form element
	 */
	protected $fields = [];

	/**
	 * this variable will holde the form and fields 
	 * to save in php session
	 * will also be used in form validation
	 */
	protected $sessionFields = [];

	
	/**
	 * current form count this is set when creating a form
	 * so that we can call only the exact form field 
	 * this is usefull for multiple form in the page
	 */
	protected $formCount = 0;
	protected $_di;

	/**
	 * this will be the cuurent field count so that we can
	 * build the field id
	 */
	protected $currentFieldCount = 0;

	public function __construct(
		\Of\Session\Session $Session,
		\Of\Std\Password $Password
	){
		$this->_session = $Session;
		$this->_password = $Password;
		$this->_di = new \Of\Std\Di();
		
		$this->_session->unsetData('form_fields');
	}

	public function form($attribute){
		$this->formCount++;

		$formName = 'form_' . $this->formCount;
		$this->fields[$formName] = [
			'attribute' => $attribute,
			'fields' => []
		];
		$this->sessionFields[$formName] = [
			'fields' => []
		];
		return $formName;
	}

	public function add($formName, $field, $row=true){
		if(isset($field['attributes'])){
			if(!isset($field['attributes']['type'])){
				$field['attributes']['type'] = 'text';
			}

			if(isset($field['row_style'])){
				$row = (bool)$field['row_style'];
			}
			
			$type = strtolower($field['attributes']['type']);

			if($type == 'text'){
				$this->addText($formName, $field, $row);
			}
			else if($type == 'email'){
				$this->addText($formName, $field, $row);
			}
			else if($type == 'password'){
				$this->addText($formName, $field, $row);
			}
			else if($type == 'number'){
				$this->addText($formName, $field, $row);
			}
			else if($type == 'button'){
				$this->addButton($formName, $field, $row);
			}
			else if($type == 'hidden'){
				$this->addHidden($formName, $field, $row);
			}
			else if($type == 'select'){
				$this->addSelect($formName, $field, $row);
			}
			else if($type == 'textarea'){
				$this->addTextArea($formName, $field, $row);
			}
		}

		return $this;
	}

	/**
	 * add button to form
	 */
	public function addButton($formName, $field, $row) {
		$id = $this->buildFieldId();

		$attr = 'id="'.$id.'"';

		$htmlClass = 'btn';
		if(isset($field['attributes']['class'])){
			$htmlClass .= " " . $field['attributes']['class'];
			$field['attributes']['class'] = $htmlClass;
		}

		foreach ($field['attributes'] as $key => $value) {
			if($key == 'type') {
				continue;
			}
			$attr .= ' ' . $key . '="' . $value . '" ';
		}

		$btn = '<button '.$attr.'>'.$field['label'].'</button>';

		$position = 'text-end';
		if(isset($field['label'])){
			if(strtolower($field['label']) == 'center'){
				$position = 'text-center';
			}
			else if(strtolower($field['label']) == 'start'){
				$position = 'text-start';
			}
		}

		$htmlBtn = '';
		if(!$row){
			$htmlBtn .= '<div class="form-group '.$position.'">';
				$htmlBtn .= $btn;
			$htmlBtn .= '</div>';
		}
		else {
			$htmlBtn .= '<div class="form-group row">';
				$htmlBtn .= '<div class="col-sm-9 ofsset-0 offset-sm-3 '.$position.'">';
					$htmlBtn .= $btn;
				$htmlBtn .= '</div>';
			$htmlBtn .= '</div>';
		}

		$this->fields[$formName]['fields'][$field['attributes']['name']] = $htmlBtn;
	}

	/**
	 * add input type text
	 */
	public function addText($formName, $field, $row=true){
		$id = $this->buildFieldId();
		$input = $this->buildInputField($field, $id);
		if(!$row){
			$tpl = '<div class="form-group">
				<label class="mb-1" for="'.$id.'">'.$this->buildLabelText($field).'</label>
				'.$input.' '.$this->buildComment($field).'
			</div>';
		}
		else {
			$tpl = '<div class="form-group row">
				<label for="'.$id.'" class="col-sm-3 col-form-label text-start text-sm-end">'.$this->buildLabelText($field).'</label>
				<div class="col-sm-9">'.$input.' '.$this->buildComment($field).'<div class="validatorjs-errors-'.$id.'"></div></div>
			</div>';
		}

		$this->fields[$formName]['fields'][$field['attributes']['name']] = $tpl;
		$this->sessionFields[$formName]['fields'][$field['attributes']['name']] = isset($field["rules"]) ? $field["rules"] : null;
	}

	public function addTextArea($formName, $field, $row=true){
		$id = $this->buildFieldId();
		$field['attributes']['id'] = $id;

		$htmlClass = 'builder-form-field form-control';
		if(isset($field['attributes']['class'])){
			$htmlClass .= " " . $field['attributes']['class'];
			unset($field['attributes']['class']);
		}

		if(isset($field['attributes']['type'])){
			unset($field['attributes']['type']);
		}

		$attr = '';
		foreach ($field['attributes'] as $key => $value) {
			$attr .= ' ' . $key . '="' . $value . '" ';
		}

		$input = '<textarea class="'.$htmlClass.'" '.$attr.'>'.$field['attributes']['value'].'</textarea>';

		if(!$row){
			$tpl = '<div class="form-group">
				<label class="mb-1" for="'.$id.'">'.$this->buildLabelText($field).'</label>
				'.$input.' '.$this->buildComment($field).'
			</div>';
		}
		else {
			$tpl = '<div class="form-group row">
				<label for="'.$id.'" class="col-sm-3 col-form-label text-start text-sm-end">'.$this->buildLabelText($field).'</label>
				<div class="col-sm-9">'.$input.' '.$this->buildComment($field).'<div class="validatorjs-errors-'.$id.'"></div></div>
			</div>';
		}

		$this->fields[$formName]['fields'][$field['attributes']['name']] = $tpl;
		$this->sessionFields[$formName]['fields'][$field['attributes']['name']] = isset($field["rules"]) ? $field["rules"] : null;
	}

	public function addSelect($formName, $field, $row=true){
		$id = $this->buildFieldId();

		$field['attributes']['id'] = $id;
		$htmlClass = 'builder-form-field form-select';
		if(isset($field['attributes']['class'])){
			$htmlClass .= " " . $field['attributes']['class'];
			unset($field['attributes']['class']);
		}

		$attr = '';
		foreach ($field['attributes'] as $key => $value) {
			$attr .= ' ' . $key . '="' . $value . '" ';
		}

		$input = '<select class="'.$htmlClass.'" '.$attr.' >';
		if(isset($field['option'])){
			$options = $this->_di->get($field['option'])->toOptionArray();
			foreach ($options as $key => $value) {
				$selected = '';
				if(isset($field['attributes']['value']) && $value['key'] == $field['attributes']['value']){
					$selected = 'selected="selected"';
				}
				$input .= '<option value="'.$value['key'].'" '.$selected.'>'.$value['value'].'</option>';
			}
		}
		$input .= '</select>';

		if(!$row){
			$tpl = '<div class="form-group">
				<label class="mb-1" for="'.$id.'">'.$this->buildLabelText($field).'</label>
				'.$input.' '.$this->buildComment($field).'
			</div>';
		}
		else {
			$tpl = '<div class="form-group row">
				<label for="'.$id.'" class="col-sm-3 col-form-label text-start text-sm-end">'.$this->buildLabelText($field).'</label>
				<div class="col-sm-9">'.$input.' '.$this->buildComment($field).'<div class="validatorjs-errors-'.$id.'"></div></div>
			</div>';
		}

		$this->fields[$formName]['fields'][$field['attributes']['name']] = $tpl;
		$this->sessionFields[$formName]['fields'][$field['attributes']['name']] = isset($field["rules"]) ? $field["rules"] : null;
	}

	/**
	 * add hidden input
	 */
	public function addHidden($formName, $field, $row=true){
		$id = $this->buildFieldId();
		
		$tpl = '<input ';
		foreach ($field['attributes'] as $key => $value) {
			$tpl .= ' ' . $key . '="' . $value . '" ';
		}
		$tpl .= ' />';

		$this->fields[$formName]['fields'][$field['attributes']['name']] = $tpl;
		$this->sessionFields[$formName]['fields'][$field['attributes']['name']] = isset($field["rules"]) ? $field["rules"] : null;
	}

	/**
	 * build label text 
	 * inlcuding the astherisk if the field is required
	 */
	protected function buildLabelText($field){
		$text = $field['label'];
		if(isset($field["rules"]) && is_array($field["rules"])){
			foreach ($field["rules"] as $key => $value) {
				if(isset($value['type']) && $value['type'] == 'required'){
					$text .= '<span class="ms-2 text-danger">*</span>';
				}
			}
		}
		return $text;
	}

	protected function buildFieldId(){
		$this->currentFieldCount++;
		return 'form-builder-' . $this->currentFieldCount;
	}

	/**
	 * build an input field
	 */
	protected function buildInputField($field, $id){
		$field['attributes']['id'] = $id;

		$htmlClass = 'builder-form-field form-control';
		if(isset($field['attributes']['class'])){
			$htmlClass .= " " . $field['attributes']['class'];
			unset($field['attributes']['class']);
		}
		$input = '<input class="'.$htmlClass.'" ';
		foreach ($field['attributes'] as $key => $value) {
			$input .= ' ' . $key . '="' . $value . '" ';
		}

		// if(isset($field['rules'])){
		// 	$validationJsRule = [];
		// 	$validationJsRuleMsg = [];
		// 	foreach ($field['rules'] as $key => $rule) {
		// 		if(isset($rule['type'])){
		// 			if($rule['type'] == 'regex'){
		// 				if(isset($rule['pattern'])){
		// 					$validationJsRule[] = $rule['type'] . ':' . $rule['pattern'];
		// 				}
		// 			}
		// 			else {
		// 				$validationJsRule[] = $rule['type'];
		// 			}

		// 			if(isset($rule['message'])){
		// 				$validationJsRuleMsg[$rule['type']] = $rule['message'];
		// 			}
		// 		}
		// 	}

		// 	if(count($validationJsRule)){
		// 		$validationJsRule = implode('|', $validationJsRule);
		// 		$input .= ' data-validation_rules="'.$validationJsRule.'" ';

		// 		if(count($validationJsRuleMsg)){
		// 			$errorMsgs = json_encode($validationJsRuleMsg);
		// 			$errorMsgs = base64_encode($errorMsgs);
		// 			$input .= ' data-validation_error_message="'.$errorMsgs.'" ';
		// 		}
		// 	}
		// }
		$input .= ' />';
		return $input;
	}

	/**
	 * build the comment for form field
	 */
	protected function buildComment($field){
		$comment = '';
		if(isset($field['comment'])){
			foreach ($field['comment'] as $key => $value) {
				$htmlClass = 'form-text';
				if(isset($value["class"])){
					$htmlClass .= " " . $value["class"];
				}
				$comment .= '<small class="'.$htmlClass.'">'.$value["text"].'</small>';
			}
		}
		return $comment;
	}

	/**
	 * return form html string 
	 */
	public function toHtml($formName, $endForm=true){
		$html = '<form id="'.$formName.'" ';
		foreach ($this->fields[$formName]['attribute'] as $key => $value) {
			$html .= ' ' . $key . '="' . $value . '" ';
		}
		$html .= '>';
		$html .= '<input type="hidden" name="form_builder_form_name" value="'.$formName.'" />';
		
		/**
		 * this value will going to be the form key
		 * so this will be used on validation of the form
		 */
		$formKey = $this->_session->getData('form_builder_form_key');
		if(!$formKey){
			$formKey = $this->_password->generate(15);
			$this->_session->setData('form_builder_form_key', $formKey);
		}
		$this->sessionFields[$formName]['form_builder_form_key'] = $formKey.'_'.$formName;

		$html .= '<input type="hidden" name="form_builder_form_key" value="'.$formKey.'_'.$formName.'" />';


		$html .= implode(' ', $this->fields[$formName]['fields']);
		if($endForm){
			$html .= '</form>';
		}
		// $html .= '<script>
		// 	require(["main"], function(main){
		// 		main._form.setFormElement("'.$formName.'");
		// 	});
		// </script>';

		$this->_session->setData('form_fields', $this->sessionFields);

		return $html;
	}
}