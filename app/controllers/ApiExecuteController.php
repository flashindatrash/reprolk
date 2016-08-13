<?php

include_once '../app/controllers/api/BaseApiController.php';

class ApiExecuteController extends BaseController {
	
	public $route;
	public $controller;
	public $form;
	
	public function beforeRender() {
		//найдем роут нашего метода
		$this->route = hasGet('method') ? Application::$routes->byName(get('method')) : null;
		
		if (is_null($this->route)) {
			$this->addAlert(sprintf(View::str('not_found'), sprintf(View::str('method_s'), get('method'))), 'danger');
			return;
		}
		
		//загрузим контроллер
		$this->controller = Application::getFactory($this->route);
		
		if (is_null($this->controller) || !$this->controller instanceof BaseApiController) {
			sprintf(View::str('error_is_not_implemented'), $this->route->name, 'BaseApiController');
			return;
		}
		
		//загрузим форму отправки из полученного контроллера
		$this->form = $this->controller->createRequestForm();
		
		//добавим необходимые ассеты для вьюхи
		$this->addJSFile('controller/ApiExecute');
		
		//выведем форму
		$this->view = 'api/execute';
	}
	
}

?>