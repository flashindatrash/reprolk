<?php

class View {
	
	public static function input() {
		$nArgs = func_num_args();
		$args = func_get_args();
		
		$name = $args[0];
		$type = $nArgs>=2 ? $args[1] : 'text';
		
		switch ($type) {
			case 'hidden':
				//name, type, value
				$value = $nArgs>=3 ? $args[2] : '';
				return '<input type="hidden" name="' . $name . '" value="' . $value . '">';
			case 'submit':
				//name, type
				return '<button type="submit" class="btn btn-primary">' . self::str($name) . '</button>';
			case 'select':
				//name, type, values
				$values = $nArgs>=3 ? $args[2] : [];
				$str = '<select class="form-control" id ="input_' . $name . '" name="' . $name . '">';
				foreach ($values as $v) $str .= '<option value="' . $v . '">' . self::str($v). '</option>';
				$str .= '</select>';
				return $str;
			case 'checkbox':
				//name, type, value
				$value = $nArgs>=3 ? $args[2] : '';
				return '<input type="checkbox" aria-label="...">';
			case 'date':
				$input = '<input class="form-control" size="16" type="text" value="" readonly><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
				$hidden = '<input type="hidden" id="input_' . $name . '" value="" />';
				$script = '<script type="text/javascript">$("#' . $name . '").datetimepicker({'.
							'weekStart: 1,' .
							'todayBtn:  1,' .
							'autoclose: 1,' .
							'todayHighlight: 1,' .
							'startView: 2,' .
							'minView: 2,' .
							'forceParse: 0' .
							'});</script>';
				return '<div class="input-group date" data-date="" data-date-format="dd MM yyyy" data-link-field="input_' . $name . '" id="' . $name . '" data-link-format="yyyy-mm-dd">' . $input . $script . '</div>' . $hidden;
			case 'any':
				//name, type, any text
				return $nArgs>=3 ? $args[2] : '';
			default:
				//name, type, value, classes
				$value = $nArgs>=3 ? $args[2] : '';
				$classes = $nArgs>=4 ? $args[3] : [];
				return '<input type="' . $type . '" value="' . $value . '" class="form-control' . (count($classes) ? ' ' . implode(' ', $classes) : '') . '" name="' . $name . '" id="input_' . $name . '" placeholder="' . self::str($name) . '">';
		}
	}
	
	
	public static function formNormal() {
		$nArgs = func_num_args();
		$args = func_get_args();
		
		$name = $args[0];
		$type = $nArgs>=2 ? $args[1] : 'text';
		
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-3 control-label">' . self::str($name) . '</label>' .
						'<div class="col-sm-9">' . self::call('input', $args) . '</div>' .
				'</div>';
	}
	
	public static function formStatic() {
		$nArgs = func_num_args();
		$args = func_get_args();
		
		$name = $args[0];
		$value = $nArgs>=2 ? $args[1] : '';
		
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-3 control-label">' . self::str($name) . '</label>' .
					'<div class="input-group col-sm-9">' .
						'<p class="form-control-static">' . $value . '</p>' .
					'</div>' .
				'</div>';
	}
	
	public static function formOffset() {
		$nArgs = func_num_args();
		$args = func_get_args();
		
		return '<div class="form-group">' .
					'<div class="col-sm-offset-3 col-sm-9">' . self::call('input', $args) . '</div>' .
				'</div>';
	}
	
	public static function str($name) {
		return Application::str($name);
	}
	
	public static function link($route_name, $text = null, $get = null, $id = null, $class = null, $title = null, $tooltip = null) {
		$route = Application::$routes->byName($route_name);
		if (is_null($route) || !$route->isAvailable()) return '';
		$href = ' href="' . $route->path . $get . '"';
		$text = is_null($text) ? $route->linkText() : $text;
		$get = is_null($get) ? '' : '?' . $get;
		$id = is_null($id) ? '' : ' id="' . $id . '"';
		$class = is_null($class) ? '' : ' class="' . $class . '"';
		$title = ' title="' . (is_null($title) ? $route->linkTitle() : $title) . '"';
		$tooltip = is_null($tooltip) ? '' : ' data-toggle="tooltip" data-placement="' . $tooltip . '"';
		return '<a' . $href . $id . $class . $title . $tooltip . '>' . $text . '</a>';
	}
	
	private static function call($method, $args) {
		return call_user_func_array(array('View', $method), $args);
	}
	
}

?>