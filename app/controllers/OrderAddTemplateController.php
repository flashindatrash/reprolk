<?php

include_once '../app/controllers/OrderAddController.php';

class OrderAddTemplateController extends OrderAddController {
	
	public $templates;
	public $template;
	
	public function beforeRender() {
		if (!hasGet('id')) {
			$this->templates = Template::getAll(Account::getGid());
			if (count($this->templates)>0) {
			
				$this->addCSSfile('selected.table');
				$this->addJSfile('selected.table');
				$this->addJSparam('view_url', Application::$routes->currentPath());
				
				$this->view = 'template/select';
				return;
			}
		}
		
		$this->template = hasGet('id') ? GroupTemplate::getAll(get('id')) : [];
		
		parent::beforeRender();
	}
	
	public function createOrderForm() {
		parent::createOrderForm();
		$this->form->setSession(reArray($this->template, 'name', 'value'));
	}
	
}

?>