<?php

Util::inc('controllers', 'base/WebController.php');

class TemplateEditController extends WebController {
	
	public $template;
	public $fields;
	public $current_fields;
	public $form;
	
	public function beforeRender() {
		if (!hasGet('id')) {
			$this->addAlert(sprintf(View::str('not_found'), View::str('template')), 'danger');
			return;
		}
		
		$tid = get('id');
		$this->template = Template::byId(get('id')); 
		
		if (is_null($this->template) || !$this->template->canEdit()) {
			$this->addAlert(sprintf(View::str('not_found'), View::str('template')), 'danger');
			return;
		}
		
		$gid = $this->template->gid;
		
		$this->form = $this->createForm('OrderTemplate');
		$this->form->loadFields(Route::ORDER_ADD, $gid);
		
		if ($this->save($tid)) {
			$this->addAlert(View::str('success_save'), 'success');
		}
		
		$template = GroupTemplate::getAll($tid);
		$this->form->setSession(reArray($template, 'name', 'value'));
		
		$this->include_datetimepicker();
		
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
	
}

?>