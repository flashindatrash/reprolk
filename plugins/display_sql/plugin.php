<?php

add_listener(HOOK_APPLICATION_RENDER, 'displaySQL_application_render');

function displaySQL_application_render($controller) {
	//все sql запросы в beforeRender отобразятся на странице
	$sql_history = Application::$db->getHistory();
	foreach ($sql_history as $sql) {
		$controller->addAlert($sql, 'info');
	}
}

?>