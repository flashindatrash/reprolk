<?php

class TemplateEditController extends BaseController {
	
	public $template;
	public $fields;
	public $current_fields;
	public $form;
	
	public function beforeRender() {
		if (!hasGet('id')) {
			$this->notfound();
			return;
		}
		
		$tid = get('id');
		$this->template = Template::byId(get('id')); 
		
		if (is_null($this->template) || !$this->template->canEdit()) {
			$this->notfound();
			return;
		}
		
		$gid = $this->template->gid;
		
		$this->form = $this->createForm('OrderTemplate');
		$this->form->loadFields(Route::ORDER_ADD, $gid);
		
		$photopolymers = reArray(GroupPhotopolymer::getAll($gid), 'pid', 'name');
		$this->form->setValue(array('pid' => $photopolymers));
		
		if ($this->save($tid)) {
			$this->addAlert(View::str('success_save'), 'success');
		}
		
		$template = GroupTemplate::getAll($tid);
		$this->form->setSession(reArray($template, 'name', 'value'));
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
		$this->view = 'template/edit';
	}
	
	public function save($tid) {
		if ($this->formValidate([])) {
			$field_names = $this->form->fieldsAll();
			$field_ids = $this->form->fieldsAllID();
			$values = $this->formValues($field_names);
			return GroupTemplate::save($tid, $field_ids, $values);
		}
		
		return false;
	}
	
	private function notfound() {
		$this->addAlert(View::str('error_template_not_found'), 'danger');
	}
	
}

?>