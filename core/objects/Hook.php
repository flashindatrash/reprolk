<?php
	define('HOOK_FORM_PARSEPOST', 'FORM_PARSEPOST');
	define('HOOK_FORM_RENDER', 'FORM_RENDER');

	/** Plugin system **/

	$listeners = array();

	/* Create an entry point for plugins */
	function hook(){
		global $listeners;

		$num_args = func_num_args();
		$args = func_get_args();

		if($num_args < 2)
			trigger_error("Insufficient arguments", E_USER_ERROR);

		// Hook name should always be first argument
		$hook_name = array_shift($args);
		// Default return
		$hook_result = array_shift($args);
		
		if(!isset($listeners[$hook_name])) {
			return $hook_result; // No plugins have registered this hook
		}
		
		$result = NULL;
		foreach($listeners[$hook_name] as $func){
			$result = call_user_func_array($func, $args);
			if ($result===NULL) {
				$result = $hook_result;
			}
		}

		return $result;
	}

	/* Attach a function to a hook */
	function add_listener($hook, $function_name){
		global $listeners;

		$listeners[$hook][] = $function_name;
	}

?>