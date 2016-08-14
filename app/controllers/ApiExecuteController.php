<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('controllers', 'api/BaseApiController.php');

class ApiExecuteController extends WebController {
	
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
		
		//проверим что контроллер наследник BaseApiController
		if (is_null($this->controller) || !$this->controller instanceof BaseApiController) {
			sprintf(View::str('error_is_not_implemented'), $this->route->name, 'BaseApiController');
			return;
		}
		
		//загрузим форму отправки из полученного контроллера
		$this->form = $this->controller->createRequestForm();
		
		//выставим дефолтные значения
		$this->form->setDefault($this->controller->getDefaultRequest());
		
		//добавим необходимые ассеты для вьюхи
		$this->addJSFile('controller/ApiExecute');
		$this->addJSparam('method_path', $this->route->path);
		
		//выведем форму
		$this->view = 'api/execute';
	}
	
}

?>