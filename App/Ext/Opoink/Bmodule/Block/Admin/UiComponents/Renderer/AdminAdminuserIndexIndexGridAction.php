<?php
namespace Opoink\Bmodule\Block\Admin\UiComponents\Renderer;

class AdminAdminuserIndexIndexGridAction  {

	protected $_url;

	public function __construct(
		\Of\Http\Url $Url
	){
		$this->_url = $Url;
	}

	public function render($fields, $name){
		$fields[$name] = '
			<a class="me-2" href="'.$this->_url->getAdminUrl('/adminuser/edit', ['admins_id' => $fields['admins_id']]).'">
				<i class="fa-solid fa-pen-to-square"></i>
			</a>
			<a 
				href="javascript:void(0)" 
				class="action-btns"
				data-type="action-confirm" 
				data-content="Are you sure you want to delete '.$fields['firstname']. ' '.$fields['lastname'].'?" 
				data-modal_title="Delete Admin User"
				data-modal_secondary="No"
				data-modal_primary="Yes"
				data-action_url="'.$this->_url->getAdminUrl('/adminuser/deleteaction', ['admins_id' => $fields['admins_id']]).'"
			/>
				<i class="fa-solid fa-trash-can"></i>
			</a>
		';
		return $fields;
	}
}

?>