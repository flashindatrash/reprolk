<?php

class View {
	
	public static function link() {
		
	}
	
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
				return '<button type="submit" class="btn btn-primary">' .  Application::str($name) . '</button>';
			case 'select':
				//name, type, values
				$values = $nArgs>=3 ? $args[2] : [];
				$str = '<select class="form-control" id ="input_' . $name . '" name="' . $name . '">';
				foreach ($values as $v) $str .= '<option value="' . $v . '">' . Application::str($v). '</option>';
				$str .= '</select>';
				return $str;
			case 'checkbox':
				//name, type, value
				$value = $nArgs>=3 ? $args[2] : '';
				return '<input type="checkbox" aria-label="...">';
			default:
				//name, type, value, classes
				$value = $nArgs>=3 ? $args[2] : '';
				$classes = $nArgs>=4 ? $args[3] : [];
				return '<input type="' . $type . '" value="' . $value . '" class="form-control' . (count($classes) ? ' ' . implode(' ', $classes) : '') . '" name="' . $name . '" id="input_' . $name . '" placeholder="' . Application::str($name) . '">';
		}
	}
	
	
	public static function formNormal() {
		$args = func_get_args();
		$name = $args[0];
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-2 control-label">' . Application::str($name) . '</label>' .
					'<div class="col-sm-9">' .
						self::call('input', $args) .
					'</div>' .
				'</div>';
	}
	
	public static function formEdited() {
		$args = func_get_args();
		$name = $args[0];
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-2 control-label">' . Application::str($name) . '</label>' .
					'<div class="input-group col-sm-9">' .
						self::call('input', $args) .
						'<span class="input-group-btn">' .
							'<button class="btn btn-default" type="button">' . Application::str('edit') . '</button>' .
						'</span>' .
					'</div>' .
				'</div>';
	}
	
	private static function call($method, $args) {
		return call_user_func_array(array('View', $method), $args);
	}
	
}

?>