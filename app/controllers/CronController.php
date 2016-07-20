<?php

class CronController extends BaseController {
	
	public function beforeRender() {
		$this->cron_plugins();
	}
	
	public function cron_plugins() {
		$this->addAlert('Plugins', 'warning');
		
		Plugin::clear();
		
		$pDir = Application::$config['plugin']['dir'];
		$pConfig = Application::$config['plugin']['config'];
		
		$plugins = $this->scandir($pDir);
		
		foreach ($plugins as $name => $value) {
			$this->addAlert(sprintf('Parse plugin %s', $value), 'warning');
			$pugin_folder = $pDir . DIRECTORY_SEPARATOR . $value;
			$struct = $this->scandir($pugin_folder);
			if (in_array($pConfig, $struct)) {
				$ini = parse_ini_file($pugin_folder . DIRECTORY_SEPARATOR . $pConfig, true);
				
				//удалим из файлов конфив
				removeArrayItem($pConfig, $struct);
				
				//получим свойства конфига
				$base = array_key_exists('base', $ini) ? $ini['base'] : [];
				$access = array_key_exists('access', $ini) ? $ini['access'] : [];
				
				if (!array_key_exists('title', $base)) {
					$this->addAlert('Config whithout title', 'warning');
					return;
				}
				
				//сохраним в базу
				Plugin::add($base['title'], $struct);
				
			} else {
				$this->addAlert('Invalid plugin', 'warning');
			}
		}
	}

	private function scandir($dir) {
		return array_diff(scandir($dir), array('..', '.'));
	}
	
	
}

?>