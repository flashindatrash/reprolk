<?php

class WebController extends BaseController {
	
	const POST_VALIDATOR = 'send';
	
	private $files = array('js' => ['jquery-1.11.3.min', 'bootstrap.min', 'bootstrap-switch.min', 'repropark'], 'css' => ['bootstrap.min', 'bootstrap-theme.min', 'bootstrap-switch.min', 'repropark']);
	private $js_params = array();
	private $menu = array();
	
	public function __construct() {
		$this->menu = MenuItem::parse(Application::$routes->all);
		
		if (Session::hasGroup()) {
			//уведомление что пользователь просматривает страницу как ...
			$this->addAlert(sprintf(View::str('warning_view_as'), 
				View::str(Session::getGroup()), 
				Session::getGid(), 
				View::link(Route::VIEW_AS_CANCEL, View::str('cancel'))
			), 'warning');
		}
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
		$count = 0;
		foreach ($items as $item) {
			if ($item->type!=$type) continue;
			$ul .= '<li class="' . $item->liClass() . '">';
			$ul .= View::link($item->routeName, null, null, null, $item->isActive() ? 'active' : null);
			if ($item->isExpanded()) {
				$ul .= $this->renderMenu($item->items, $type);
			}
			$ul .= '</li>';
			$count++;
		}
		$ul .= '</ul>';
		return $count > 0 ? $ul : '';
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
	
	public function formValidate($fields) {
		if (!toBool(post(self::POST_VALIDATOR))) return false;
		return parent::formValidate($fields);
	}
	
}

?>