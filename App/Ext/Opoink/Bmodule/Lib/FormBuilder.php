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
	 * the value of this field is a form field html string 
	 * it is a bootsrap 5 form element
	 */
	protected $fields = [];

	
	/**
	 * current form count this is set when creating a form
	 * so that we can call only the exact form field 
	 * this is usefull for multiple form in the page
	 */
	protected $formCount = 0;

	/**
	 * this will be the cuurent field count so that we can
	 * build the field id
	 */
	protected $currentFieldCount = 0;

	public function __construct(
		\Of\Session\Session $Session
	){
		$this->_session = $Session;
	}

	public function form($attribute){
		$this->formCount++;

		$formName = 'form_' . $this->formCount;
		$this->fields[$formName] = [
			'attribute' => $attribute,
			'fields' => []
		];
		return $formName;
	}

	public function add($formName, $field, $row=true){
		if(!isset($field['type'])){
			$field['type'] = 'text';
		}

		if(strtolower($field['type']) == 'text'){
			$this->addText($formName, $field, $row);
		}

		return $this;
	}

	/**
	 * add input type text
	 */
	public function addText($formName, $field, $row=true){
		$id = $this->buildFieldId();
		$input = $this->buildInputField($field, $id);
		if(!$row){
			$tpl = '<div class="form-group">
				<label for="'.$id.'">'.$field['label'].'</label>
				'.$input.' '.$this->buildComment($field).'
			</div>';
		}
		else {
			$tpl = '<div class="form-group row">
				<label for="'.$id.'" class="col-sm-3 col-form-label text-end">'.$field['label'].'</label>
				<div class="col-sm-9">'.$input.' '.$this->buildComment($field).'</div>
			</div>';
		}

		$this->fields[$formName]['fields'][$field['attributes']['name']] = $tpl;
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
		$input = '<input class="form-control" ';
		foreach ($field['attributes'] as $key => $value) {
			$input .= ' ' . $key . '="' . $value . '" ';
		}
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
				$comment .= '<small class="form-text text-muted">'.$value.'</small>';
			}
		}
		return $comment;
	}

	public function toHtml($formName){
		$html = '<form id="'.$formName.'" ';
		foreach ($this->fields[$formName]['attribute'] as $key => $value) {
			$html .= ' ' . $key . '="' . $value . '" ';
		}
		$html .= '>';

		$html .= implode(' ', $this->fields[$formName]['fields']);
		$html .= '</form>';

		return $html;
	}
}