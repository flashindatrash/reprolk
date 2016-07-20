<?php

class BaseController {
	
	private $template = 'base';
	private $title = 'Repropark';
	private $files = array('js' => ['jquery-1.11.3.min', 'bootstrap.min', 'bootstrap-switch.min', 'repropark'], 'css' => ['bootstrap.min', 'bootstrap-theme.min', 'bootstrap-switch.min', 'repropark']);
	private $js_params = array();
	private $menu = array();
	
	protected $alerts = array('danger' => [], 'warning' => [], 'info' => [], 'success' => []);
	protected $view;
	
	public function __construct() {
		$this->menu = MenuItem::parse(Application::$routes->all);
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function createForm($name) {
		$php = Application::$config['app']['forms'] . $name . '.php';
		$this->include_file($php);
		$className = $name . 'Form';
		if (class_exists($className)) {
			return new $className();
		} else {
			$this->addAlert(sprintf('Fail Form: path %s not found class %s', $php, $className), 'danger');
		}
		return null;
	}
	
	public function addCSSfile($css) {
		$this->files['css'][] = $css;
	}
	
	public function addJSfile($js) {
		$this->files['js'][] = $js;
	}
	
	public function addJSparam($name, $value) {
		$this->js_params[$name] = $value;
	}
	
	public function addAlert($message, $type = 'danger') {
		$this->alerts[$type][] = $message;
	}
	
	public function beforeRender() {
		//до того как загрузиться шаблон мы должны заинитить контроллер
	}
	
	public function getTitle() {
		print $this->title;
	}
	
	public function getCSSfiles() {
		foreach ($this->files['css'] as $css) {
			print '<link rel="stylesheet" href="' . Application::$config['public']['css'] . $css . '.css">';
		}
	}
	
	public function getJSfiles() {
		foreach ($this->files['js'] as $js) {
			$filename = Application::$config['public']['js'] . $js . '.js';
			if (filter_var($js, FILTER_VALIDATE_URL)) $filename = $js;
			print '<script src="' . $filename . '"></script>';
		}
	}
	
	public function getJSparams() {
		print '<script>var params = ' . json_encode($this->js_params) . ';</script>';
	}
	
	public function getMessages($type) {
		foreach ($this->alerts[$type] as $message) {
			print '<div class="alert alert-' . $type . '" role="alert">' . $message . '</div>';
		}
	}
	
	public function getBreadcrumbs() {
		$breadcrumb = $this->generateBreadcrump($this->menu);
		print !Account::isLogined() || $breadcrumb=='' ? '' : '<ol class="breadcrumb">' . $breadcrumb . '</ol><hr>';
	}
	
	public function getMenu() {
		print $this->renderMenu($this->menu, Route::TYPE_NORMAL);
	}
	
	public function getSubMenu() {
		print $this->renderMenu($this->menu, Route::TYPE_SUB);
	}
	
	public function getLanguages() {
		if (!Account::isLogined()) return;
		$li = array();
		foreach (Locale::getLanguages() as $language) {
			if ($language==Account::getLang()) {
				$li[] = $language;
			} else {
				$li[] = '<li>' . View::link(Route::LANGUAGE_SET, $language, 'l=' . $language) . '</li>';
			}
		}
		print '<ul class="list-inline">' . implode('', $li) . '</ul>';
	}
	
	public function getContent() {
		if (is_null($this->view)) return;
		$this->pick($this->view);
	}
	
	public function formValidate($fields) {
		if (post('send')!='1') return false;
		$valid = true;
		foreach ($fields as $field) {
			if (!hasPost($field)) {
				$valid = false;
				$this->addAlert(sprintf(View::str('must_enter'), View::str($field)), 'danger');
			}
		}
		return $valid;
	}
	
	public function formValues($fields) {
		$values = array();
		foreach ($fields as $field) {
			$values[] = post($field);
		}
		return $values;
	}
	
	public function pick($path) {
		$phtml = Application::$config['app']['content'] . $path . '.phtml';
		$this->include_file($phtml);
	}
	
	public function render() {
		$this->include_file(Application::$config['app']['layouts'] . $this->template . '.phtml');
	}
	
	protected function applyPaginator(&$currentPage, &$totalPages, $total, $perPage) {
		$totalPages = ceil($total / $perPage);
		$currentPage = hasGet('page') ? get('page') : 0;
		if ($currentPage>$totalPages-1) $currentPage = $totalPages-1;
		else if ($currentPage<0) $currentPage = 0;
	}
	
	protected function generateBreadcrump($items) {
		return $this->renderBreadcrump($items);
	}
	
	protected function include_datetimepicker() {
		$this->addJSfile('datetimepicker/datetimepicker.min');
		$this->addJSfile('datetimepicker/locales/bootstrap-datetimepicker.' . Account::getLang());
		$this->addCSSfile('datetimepicker');
	}
	
	protected function include_fileinput() {
		$this->addJSfile('fileinput/canvas-to-blob.min');
		$this->addJSfile('fileinput/fileinput.min');
		$this->addJSfile('fileinput/locales/fileinput_locale_' . Account::getLang());
		$this->addCSSfile('fileinput');
	}
	
	private function renderMenu($items, $type) {
		$ul = '<ul class="menu">';
		foreach ($items as $item) {
			if ($item->type!=$type) continue;
			$ul .= '<li class="' . $item->liClass() . '">';
			$ul .= View::link($item->routeName, null, null, null, $item->isActive() ? 'active' : null);
			if ($item->isExpanded()) {
				$ul .= $this->renderMenu($item->items, $type);
			}
			$ul .= '</li>';
		}
		$ul .= '</ul>';
		return $ul;
	}
	
	private function renderBreadcrump($items) {
		if (!is_null(Application::$routes->current) && Application::$routes->current->isAvailable()) {
			foreach ($items as $item) {
				if ($item->isFinded()) {
					if ($item->isActive()) return '<li class="active">' . $item->breadcrumpText() . '</li>';
					else {
						$get = null;
						if ($item->routeName == Route::ORDER_VIEW && hasGet('id')) {
							$get = 'id=' . get('id');
						}
						return '<li>' . View::link($item->routeName, null, $get) . '</li>' . $this->renderBreadcrump($item->items);
					}
				}
			}
		}
		return '';
	}
	
	private function include_file($file) {
		if (file_exists($file)) include_once $file;
	}

}