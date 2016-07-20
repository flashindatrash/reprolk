<?php

add_listener(HOOK_FORM_PARSEPOST, 'archive2title_form_parsePost');
add_listener(HOOK_FORM_RENDER, 'archive2title_form_render');

function archive2title_form_parsePost($name) {
	if ($name!='title') return null;
	
	$attachments = isset($_FILES['files']) ? reArrayFiles($_FILES['files']) : [];
	$files = array();
	
	foreach($attachments as $file) {
		if ($file['type']=='application/octet-stream') {
			$files[] = pathinfo($file['name'], PATHINFO_FILENAME);
		}
	}
	
	if (count($files) && !hasPost('title')) {
		$name = join(' / ', $files);
		$_POST['title'] = $name;
		return $name;
	}
	
	return null;
}

function archive2title_form_render($field) {
	if ($field->name!='title') return null;
	
	return View::formNormal($field->name, $field->type, $field->getValue(), null, View::str('archive2title_title_autoinput'));
}

?>