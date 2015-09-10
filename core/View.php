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
				$value = $nArgs>=3 ? $args[2] : '';
				return '<input type="hidden" name="' . $name . '" value="' . $value . '">';
			case 'submit':
				return '<button type="submit" class="btn btn-primary">' .  Application::str($name) . '</button>';
			case 'select':
				$values = $nArgs>=3 ? $args[2] : [];
				$str = '<select class="form-control" id ="input_' . $name . '" name="' . $name . '">';
				foreach ($values as $v) $str .= '<option value="' . $v . '">' . Application::str($v). '</option>';
				$str .= '</select>';
				return $str;
			default:
				return '<input type="' . $type . '" class="form-control" name="' . $name . '" id="input_' . $name . '" placeholder="' . Application::str($name) . '">';
		}
	}
	
	
	public static function group() {
		$args = func_get_args();
		
		$name = $args[0];
		
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-2 control-label">' . Application::str($name) . '</label>' .
					'<div class="col-sm-10">' .
						self::call('input', $args) .
					'</div>' .
				'</div>';
	}
	
	private static function call($method, $args) {
		return call_user_func_array(array('View', $method), $args);
	}
	
}

?>