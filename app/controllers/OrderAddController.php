<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../app/controllers/BaseOrderController.php';

class OrderAddController extends BaseOrderController implements IRedirect {
	
	public $form;
	public $photopolymers;
	public $templates;
	public $template_id;
	public $template_fields;
	
	public function beforeRender() {
		$gid = Account::getGid();
		$this->photopolymers = reArray(GroupPhotopolymer::getAll($gid), 'pid', 'name');
		$this->templates = Template::getAll($gid);
		
		$this->generateTemplate();
		
		$this->createOrderForm();
		
		if ($this->add()) {
			$this->view = 'system/redirect';
			return;
		}
		
		if (count($this->photopolymers)==0)  {
			$this->addAlert(View::str('error_not_have_photopolymer'), 'warning');
			return;
		}
		
		$this->include_datetimepicker();
		$this->include_fileinput();
		
		$this->view = 'order/add';
	}
	
	public function createOrderForm() {
		$this->form = $this->createForm($this->getFormName());
		$this->form->loadFields(Route::ORDER_ADD);
		if ($this->template_id!=0) {
			$this->form->setSession(reArray($this->template_fields, 'name', 'value'));
		}
		$this->form->setValue(array('pid' => $this->photopolymers));
		$this->form->setSession(array('date_due' => (new DateTime('tomorrow'))->format("Y-m-d")));
	}
	
	//IRedirect
	public function getRedirect() {
		return new Redirect(View::str('order_successfuly'), Application::$routes->byName(Route::ORDER_ALL)->path);
	}
	
	public function add() {
		if ($this->formValidate($this->form->fieldsMandatory())) {
			$fields = $this->form->fieldsAll();
			$values = $this->formValues($fields);
			
			$oid = Order::add($fields, $values);
			
			if (!is_null($oid)) {
				if (!$this->loadOrder($oid)) return false;
				
				//добавляем коммент
				if (hasPost(Order::FIELD_COMMENT)) {
					Comment::add($oid, post(Order::FIELD_COMMENT), $this->order->status);
				}
				
				if ($this->save(isset($_FILES['files']) ? $_FILES['files'] : null)) {
					return true;
				} else {
					$this->order->remove(true);
				}
			}
		}
		
		return false;
	}
	
	protected function showFiles() {
		return true;
	}
	
	protected function getFormName() {
		return 'OrderAdd';
	}
	
	protected function generateTemplate() {
		$this->template_id = hasGet('template') ? get('template') : 0;
		$this->template_fields = $this->template_id!=0 ? GroupTemplate::getAll($this->template_id) : [];
	}
	
	protected function generateBreadcrump($items) {
		$menu = '';
		
		if (count($this->templates)>0) {
			$li = array();
			$li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_ADD, View::str('new_form')), $this->template_id==0);
			$li[] = View::rightBreadcrumpLink(null); //separator
			foreach ($this->templates as $template) {
				$li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_ADD, $template->name, 'template=' . $template->id), $this->template_id==$template->id);
			}
			
			$menu = View::rightBreadcrumpDropdown(View::str('select_template'), $li);
		}
		
		return parent::generateBreadcrump($items) . $menu;
	}
	
}

?>