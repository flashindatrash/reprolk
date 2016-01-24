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
				//name, type, classes
				$class = $nArgs>=3 ? $args[2] : 'btn btn-primary';
				return '<button type="submit" class="'. $class .'">' . self::str($name) . '</button>';
			case 'select':
			case 'multiple':
				//name, type (text), values ([]), useKeys (false), localisate (true), value (null), size (5)
				$values = $nArgs>=3 ? $args[2] : [];
				$useKeys = $nArgs>=4 ? $args[3] : false;
				$localisate = $nArgs>=5 ? $args[4] : true;
				$value = $nArgs>=6 ? $args[5] : null;
				$size = $nArgs>=7 ? $args[6] : 5;
				$str = '<select' . ($type=='multiple' ? ' multiple' : ' ') . ' class="form-control" id ="input_' . $name . '" name="' . $name . ($type=='multiple' ? '[]' : '') . '"' . ($type=='multiple' ? ' size="' . $size . '"' : '') . '>';
				if (!is_null($values) && is_array($values)) { 
					foreach ($values as $i => $v) {
						$c = $useKeys ? $i : $v;
						$selected = !is_null($value) && (is_array($value) ? in_array($c, $value) : $value==$c) ? ' selected' : '';
						$str .= '<option value="' . $c . '"' . $selected . '>' . ($localisate ? self::str($v) : $v). '</option>';
					}
				}
				$str .= '</select>';
				return $str;
			case 'checkbox':
				//name, type, value
				$value = $nArgs>=3 ? ($args[2]==1 || $args[2]===true) : false;
				$checked = $value ? ' checked' : '';
				return '<div class="checkbox"><label><input type="checkbox" name="' . $name . '" id ="input_' . $name . '" aria-label="..."' . $checked . '> ' . self::str($name) . '</label></div>';
			case 'date':
				//name, type, value, useBackDate (true)
				$value = $nArgs>=3 && !is_null($args[2]) ? $args[2] : '';
				$useBackDate = $nArgs>=4 ? $args[3] : true;
				$input = '<input class="form-control" size="16" type="text" value="' . ($value!='' ? date("d F Y", strtotime($value)) : '') . '" readonly>' .
							'<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>' .
							'<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
							
				
				$hidden = '<input type="hidden" name="' . $name . '" id="input_' . $name . '" value="' . $value . '" />';
				$script = '<script type="text/javascript">$("#' . $name . '").datetimepicker({'.
							'weekStart: 1,' .
							'todayBtn:  1,' .
							'autoclose: 1,' .
							'todayHighlight: 1,' .
							'startView: 2,' .
							'minView: 2,' .
							'pickerPosition: "bottom-left",' .
							($useBackDate ? 'startDate: new Date(),' : '') .
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
		
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-3 control-label">' . self::str($name) . '</label>' .
						'<div class="col-sm-9">' . self::call('input', $args) . '</div>' .
				'</div>';
	}
	
	public static function formSmall() {
		$nArgs = func_num_args();
		$args = func_get_args();
		
		$name = $args[0];
		
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-2 control-label">' . self::str($name) . '</label>' .
						'<div class="col-sm-10">' . self::call('input', $args) . '</div>' .
				'</div>';
	}
	
	public static function formAny() {
		$nArgs = func_num_args();
		$args = func_get_args();
		
		$name = $args[0];
		$value = $nArgs>=2 ? $args[1] : '';
		$cols = $nArgs>=3 ? $args[2] : 3;
		
		return	'<div class="form-group">' .
					'<label for="input_' . $name . '" class="col-sm-' . $cols . ' control-label">' . self::str($name) . '</label>' .
						$value .
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
	
	public static function linkSort($field, $order) {
		$by = $order->by == 'desc' ? 'asc' : 'desc';
		$gets = gets();
		$gets['sort'] = $field;
		$gets['by'] = $by;
		$html = '<a href="?' . self::getValues($gets) . '">' . self::str( $field ) . '</a>';
		if ($field==$order->field) $html .= self::orderBy($order);
		return $html;
	}
	
	public static function attachments($files) {
		$html = '<ul>';
		foreach ($files as $file) {
			$html .= '<li>' . self::attachment($file) . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	
	public static function attachment($file) {
		$size = self::formatSizeUnits($file->size);
		$text = $file->name;
		return self::link(Route::FILE, $text, 'id=' . $file->id, null, null, null, null, null, '_blank') . ' [' . $size . ']';
	}
	
	public static function link($r, $text = null, $get = null, $id = null, $class = null, $title = null, $tooltip = null, $data = null, $target = '_self') {
		if ($r=='#') $r = new Route(null, '#');
		$route = (!$r instanceof Route) ? Application::$routes->byName($r) : $r;
		if (is_null($route) || !$route->isAvailable()) return '';
		$text = is_null($text) ? $route->linkText() : $text;
		$get = is_null($get) ? '' : '?' . $get;
		$href = ' href="' . $route->path . $get . '"';
		$id = is_null($id) ? '' : ' id="' . $id . '"';
		$class = is_null($class) ? '' : ' class="' . $class . '"';
		$target = ' target="' . $target . '"';
		$title = ' title="' . (is_null($title) ? $route->linkTitle() : $title) . '"';
		$attr = '';
		if (is_null($data)) {
			$data = array();
		}
		if (!is_null($tooltip)) {
			$data['toggle'] = 'tooltip';
			$data['placement'] = $tooltip;
		}
		if (is_array($data)) {
			foreach ($data as $key => $data_value) {
				$attr .= ' data-' . $key . '="' . $data_value . '"';
			}
		}
		return '<a' . $href . $id . $class . $title . $attr . $target . '>' . $text . '</a>';
	}
	
    public static function formatSizeUnits($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} else if ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} else if ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} else if ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} else if ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}
		return $bytes;
	}
	
	public static function bool2icon($value) {
		return self::icon($value===true || $value==1 ? 'ok' : 'minus');
	}
	
	public static function icon($value) {
		return '<span class="glyphicon glyphicon-' . $value . '" aria-hidden="true"></span>';
	}
	
	public static function getValues($arr) {
		return http_build_query($arr);
	}
	
	private static function orderBy($order) {
		return '<span class="orderby glyphicon glyphicon-chevron-' . ($order->by=='desc' ? 'down' : 'up') . '" aria-hidden="true"></span>';
	}
	
	private static function call($method, $args) {
		return call_user_func_array(array('View', $method), $args);
	}
	
}

?>