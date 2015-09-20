<?php

class BaseController {
	
	private $template = 'base';
	private $title = 'Repropark';
	private $css_files = array('bootstrap.min', 'bootstrap-theme.min', 'repropark');
	private $js_files = array('jquery-1.11.3.min', 'bootstrap.min', 'repropark');
	private $js_params = array();
	private $errors = array();
	private $warnings = array();
	private $menu = array();
	
	public function __construct() {
		$this->menu = MenuItem::parse(Application::$routes->all);
	}
	
	public function addCSSfile($css) {
		$this->css_files[] = $css;
	}
	
	public function addJSfile($js) {
		$this->js_files[] = $js;
	}
	
	public function addJSparam($name, $value) {
		$this->js_params[$name] = $value;
	}
	
	public function addError($message) {
		$this->errors[] = $message;
	}
	
	public function addWarning($message) {
		$this->warnings[] = $message;
	}
	
	public function beforeRender() {
		//до того как загрузиться шаблон мы должны заинитить контроллер
	}
	
	public function getTitle() {
		print $this->title;
	}
	
	public function getCSSfiles() {
		foreach ($this->css_files as $css) {
			print '<link rel="stylesheet" href="' . Application::$config['public']['css'] . $css . '.css">';
		}
	}
	
	public function getJSfiles() {
		foreach ($this->js_files as $js) {
			print '<script src="' . Application::$config['public']['js'] . $js . '.js"></script>';
		}
	}
	
	public function getJSparams() {
		$js = '<script>var params = {';
		foreach ($this->js_params as $key => $value) {
			$js .= $key . ': "' . $value . '",';
		}
		$js .= '};</script>';
		print $js;
	}
	
	public function getWarnings() {
		foreach ($this->warnings as $warning) {
			print '<div class="alert alert-warning" role="alert">' . $warning . '</div>';
		}
	}
	
	public function getErrors() {
		foreach ($this->errors as $error) {
			print '<div class="alert alert-danger" role="alert">' . $error . '</div>';
		}
	}
	
	public function getBreadcrumbs() {
		$breadcrumb = $this->renderBreadcrump($this->menu);
		print !Account::isLogined() || $breadcrumb=='' ? '' : '<ol class="breadcrumb">' . $breadcrumb . '</ol><hr>';
	}
	
	public function getMenu() {
		print $this->renderMenu($this->menu);
	}
	
	public function getContent() {
		print '';
	}
	
	public function formValidate($fields) {
		if (post('send')!='1') return null;
		
		$values = array();
		
		$valid = true;
		
		foreach ($fields as $field) {
			if (!hasPost($field)) {
				$valid = false;
				$this->addError(sprintf(View::str('must_enter'), View::str($field)));
			} else {
				$values[] = post($field);
			}
		}
		
		return $valid ? $values : null;
	}
	
	public function pick($name) {
		$this->include_file(Application::$config['app']['content'] . $name . '.phtml');
	}
	
	public function render() {
		$this->include_file(Application::$config['app']['layouts'] . $this->template . '.phtml');
	}
	
	private function renderMenu($items) {
		$ul = '<ul class="menu">';
		foreach ($items as $item) {
			if (!$item->visible) continue;
			$ul .= '<li class="' . $item->liClass() . '">';
			$ul .= View::link($item->routeName, null, null, null, $item->isActive() ? 'active' : null);
			if ($item->isExpanded()) {
				$ul .= $this->renderMenu($item->items);
			}
			$ul .= '</li>';
		}
		$ul .= '</ul>';
		return $ul;
	}
	
	private function renderBreadcrump($items) {
		foreach ($items as $item) {
			if ($item->isFinded()) {
				if ($item->isActive()) return '<li class="active">' . $item->linkText() . '</li>';
				else return '<li>' . View::link($item->routeName) . '</li>' . $this->renderBreadcrump($item->items);
			}
		}
		return '';
	}
	
	private function include_file($file) {
		if (file_exists($file)) include $file;
	}

}