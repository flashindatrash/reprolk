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
			case 'switch':
			case 'checkbox':
				//name, type, value, datas (Array)
				$value = $nArgs>=3 ? ($args[2]==1 || $args[2]===true) : false;
				$datas = $nArgs>=4 ? $args[3] : [];
				$checked = $value ? ' checked' : '';
				$data = $type=='switch' ? ' data-switch="enabled"' : '';
				if (count($datas)>0) {
					$data .= ' ' . join(' ', $datas);
				}
				return '<div class="checkbox"><label><input type="checkbox" name="' . $name . '" id ="input_' . $name . '" aria-label="..."' . $data . $checked . '> ' . self::str($name) . '</label></div>';
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
							'language: "' . Account::getLang() . '",' .
							($value!='' ? 'initialDate: new Date("' . $value . '"),' : '') .
							'forceParse: 0' .
							'});</script>';
				return '<div class="input-group date" data-date="" data-date-format="dd MM yyyy" data-link-field="input_' . $name . '" id="' . $name . '" data-link-format="yyyy-mm-dd">' . $input . $script . '</div>' . $hidden;
			case 'textarea':
				//name, type, value, rows (3)
				$value = $nArgs>=3 ? $args[2] : '';
				$rows = $nArgs>=4 ? $args[3] : 3;
				return '<textarea class="form-control" name="' . $name . '" id="input_' . $name . '" rows="' . $rows . '" placeholder="' . self::str($name) . '">' . $value . '</textarea>';
			case 'file': case 'files': 
				//name, type, extensions, maxFileSize, maxFileCount
				$extensions = $nArgs>=3 ? $args[2] : [];
				$maxFileSize = $nArgs>=4 ? $args[3] : 0;
				$maxFileCount = $nArgs>=5 ? $args[4] : $type=='file' ? 1 : 0;
				
				$n = $type=='file' ? 'name="' . $name . '"' : 'name="' . $name . '[]" multiple';
				$s = ' data-max-file-size="' . $maxFileSize . '"';
				$c = ' data-max-file-count="' . $maxFileCount . '"';
				$e = count($extensions)>0 ? ' data-allowed-file-extensions=\'["' . implode('", "', $extensions) . '"]\'' : '';
				$input = '<input ' . $n . $e . $s . $c . ' 
					type="file" 
					class="file" 
					data-upload-async="false" 
					data-show-preview="false" 
					data-show-upload="false" 
					data-show-caption="true" 
					data-language="' . Account::getLang() . '">';
				if (count($extensions)>0) {
					$sup = '<p class="pull-right file-extensions">' . implode(', ', $extensions) . '</p>';
				}
				return $input . $sup;
			case 'any':
				//name, type, any text
				return $nArgs>=3 ? $args[2] : '';
			default:
				//name, type, value, classes, placeholder (name)
				$value = $nArgs>=3 ? $args[2] : '';
				$classes = $nArgs>=4 && $args[3] ? $args[3] : [];
				$placeholder = $nArgs>=5 ? $args[4] : self::str($name);
				return '<input type="' . $type . '" value="' . $value . '" class="form-control' . (count($classes) ? ' ' . implode(' ', $classes) : '') . '" name="' . $name . '" id="input_' . $name . '" placeholder="' . $placeholder . '">';
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
	
	public static function rightBreadcrumpDropdown($select, $li) {
		$html = '<div class="dropdown pull-right">';
		$html .= '<a class="dropdown-toggle" id="templates" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' . $select . '&nbsp;<span class="caret"></span></a>';
		$html .= '<ul class="dropdown-menu" aria-labelledby="templates">' . implode('', $li) . '</ul>';
		$html .= '</div>';
		return $html;
	}
	
	public static function rightBreadcrumpLink($link, $isActive = false) {
		if (is_null($link)) {
			return '<li role="separator" class="divider"></li>';
		} else if ($link=='') return ''; //если права не позволяет, не оббертываем в li
		return '<li' . ($isActive ? ' class="active"' : '') . '>' . $link . '</li>';
	}
	
	public static function paginator($count, $current, $buttons = 9) {
		if ($count<=1) return '';
		$html = '<nav>';
		$html .= '<ul class="pagination">';
		$html .= '<li><a href="?' . self::paginatorPage(0) . '" aria-label="' . self::str('previous') . '"><span aria-hidden="true">&laquo;</span></a></li>';
		
		$offset = floor(($buttons-1)/2);
		$min = max($current-$offset, 0);
		$max = min($current + 1 +$offset, $count);
		
		for ($i = $min; $i<$max; $i++) {
			$html .= '<li' . ($i==$current ? ' class="active"' : '') . '><a href="?' . self::paginatorPage($i) . '">' . ($i + 1) . '</li>';
		}
		$html .= '<li><a href="?' . self::paginatorPage($count - 1) . '" aria-label="' . self::str('next') . '"><span aria-hidden="true">&raquo;</span></a></li>';
		$html .= '</ul>';
		$html .= '</nav>';
		return $html;
	}
	
	public static function paginatorPage($page) {
		$gets = gets();
		$gets['page'] = $page;
		return Util::httpQuery($gets);
	}
	
	public static function linkSort($field, $order) {
		$by = 'desc';
		if ($order->field == $field) {
			$by = $order->by == 'desc' ? 'asc' : 'desc';
		}
		$gets = gets();
		$gets['sort'] = $field;
		$gets['by'] = $by;
		$html = '<a href="?' . Util::httpQuery($gets) . '">' . self::str( $field ) . '</a>';
		if ($field==$order->field) $html .= self::orderBy($order);
		return $html;
	}
	
	public static function plugin($name, $isEnabled) {
		$html = '';
		$html .= '<fieldset class="plugin">';
		$html .= '<legend>' . $name . '</legend>';
		$html .= '<div class="plugin-content">';
		$html .= self::str('plugin_' . $name);
		$html .= self::input('', 'switch', $isEnabled, ['data-type="plugin"', 'data-path="' . Route::byName(Route::SWITCH_PLUGIN)->path . '"', 'data-name="' . $name . '"']);
		$html .= '</div>';
		$html .= '</fieldset>';
		return $html;
	}
	
	public static function attachments($files) {
		$html = '<ul class="files">';
		foreach ($files as $file) {
			$html .= '<li>' . self::attachment($file) . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	
	public static function attachment($file) {
		$extension = '<li class="list-group-item list-group-item-success col-sm-1 extension">' . $file->extension() . '</li>';
		$name = '<li class="list-group-item col-sm-7 name">' . $file->name . '</li>';
		$size = '<li class="list-group-item col-sm-2 list-group-item-warning">' . self::formatSizeUnits($file->size) . '</li>';
		$link = self::link(Route::FILE, self::str('download'), 'id=' . $file->id, null, 'list-group-item col-sm-2 active', null, null, null, '_blank') ;
		return '<ul class="file list-group-horizontal row">' . $extension . $name . $size . $link . '</ul>';
	}
	
	public static function link($r, $text = null, $get = null, $id = null, $class = null, $title = null, $tooltip = null, $data = null, $target = '_self') {
		if ($r=='#') $r = new Route(null, '#');
		$route = (!$r instanceof Route) ? Route::byName($r) : $r;
		if (is_null($route) || !$route->isAvailable()) return '';
		$text = is_null($text) ? $route->linkText() : $text;
		$href = ' href="' . $route->forGet($get) . '"';
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
	
	public static function bool2str($value) {
		return self::str($value===true || $value==1 ? 'yes' : 'no');
	}
	
	public static function icon($value) {
		return '<span class="glyphicon glyphicon-' . $value . '" aria-hidden="true"></span>';
	}
	
	private static function orderBy($order) {
		return '<span class="orderby glyphicon glyphicon-chevron-' . ($order->by=='desc' ? 'down' : 'up') . '" aria-hidden="true"></span>';
	}
	
	private static function call($method, $args) {
		return call_user_func_array(array('View', $method), $args);
	}
	
}