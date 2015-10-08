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
			case 'select': case 'multiple':
				//name, type (text), values ([]), useKeys (false), localisate (true), value (null)
				$values = $nArgs>=3 ? $args[2] : [];
				$useKeys = $nArgs>=4 ? $args[3] : false;
				$localisate = $nArgs>=5 ? $args[4] : true;
				$value = $nArgs>=6 ? $args[5] : null;
				$str = '<select' . ($type=='multiple' ? ' multiple' : ' ') . ' class="form-control" id ="input_' . $name . '" name="' . $name . ($type=='multiple' ? '[]' : '') . '">';
				if (!is_null($values)) { 
					foreach ($values as $i => $v) {
						$c = $useKeys ? $i : $v;
						$selected = !is_null($value) && $value==$c ? ' selected' : '';
						$str .= '<option value="' . $c . '"' . $selected . '>' . ($localisate ? self::str($v) : $v). '</option>';
					}
				}
				$str .= '</select>';
				return $str;
			case 'checkbox':
				//name, type, value
				$value = $nArgs>=3 ? $args[2]==="1" || $args[2]===true : false;
				$checked = $value ? ' checked' : '';
				return '<div class="checkbox"><label><input type="checkbox" name="' . $name . '" id ="input_' . $name . '" aria-label="..."' . $checked . '> ' . self::str($name) . '</label></div>';
			case 'date':
				//name, type, value
				$value = $nArgs>=3 ? $args[2] : '';
				$input = '<input class="form-control" size="16" type="text" value="' . ($value!='' ? date("d F Y", strtotime($value)) : '') . '" readonly><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
				$hidden = '<input type="hidden" name="' . $name . '" id="input_' . $name . '" value="' . $value . '" />';
				$script = '<script type="text/javascript">$("#' . $name . '").datetimepicker({'.
							'weekStart: 1,' .
							'todayBtn:  1,' .
							'autoclose: 1,' .
							'todayHighlight: 1,' .
							'startView: 2,' .
							'minView: 2,' .
							'pickerPosition: "bottom-left",' .
							'startDate: new Date(),' .
							'language: "en",' .
							($value!='' ? 'initialDate: new Date("' . $value . '"),' : '') .
							'forceParse: 0' .
							'});</script>';
				return '<div class="input-group date" data-date="" data-date-format="dd MM yyyy" data-link-field="input_' . $name . '" id="' . $name . '" data-link-format="yyyy-mm-dd">' . $input . $script . '</div>' . $hidden;
			case 'textarea':
				//name, type, value
				$value = $nArgs>=3 ? $args[2] : '';
				return '<textarea class="form-control" name="' . $name . '" id="input_' . $name . '" placeholder="' . self::str($name) . '">' . $value . '</textarea>';
			case 'file':
				//name, type
				return '<input type="file" name="' . $name . '">';
			case 'files':
				//name, type
				return '<input type="file" name="' . $name . '[]" multiple>';
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
					'<label for="input_' . $name . '" class="col-sm-3 control-label">' . self::str($name) . ':</label>' .
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
	
	public static function formValidate() {
		return self::input('send', 'hidden', '1');
	}
	
	public static function str($name) {
		return Application::str($name);
	}
	
	public static function bool($value) {
		return self::str($value=='1' ? 'yes' : 'no');
	}
	
	public static function convertSelect($array, $key, $value) {
		$a = array();
		foreach ($array as $item) {
			$a[$item->$key] = $item->$value;
		}
		return $a;
	}
	
	public static function link($r, $text = null, $get = null, $id = null, $class = null, $title = null, $tooltip = null) {
		$route = (!$r instanceof Route) ? Application::$routes->byName($r) : $r;
		if (is_null($route) || !$route->isAvailable()) return '';
		$text = is_null($text) ? $route->linkText() : $text;
		$get = is_null($get) ? '' : '?' . $get;
		$href = ' href="' . $route->path . $get . '"';
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