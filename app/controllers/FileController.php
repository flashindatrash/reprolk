<?php

class FileController extends BaseController {
	
	public $file;
	public $view;
	public $content;
	
	public function beforeRender() {
		if (!hasGet('id')) return;
		
		$this->file = File::byId(get('id'));
		
		if (is_null($this->file) || strlen($this->file->content)==0) return;
		
		$this->setTitle($this->file->name);
		
		switch ($this->file->type) {
			case 'image/png':
			case 'image/jpeg':
				$this->setTemplate('empty');
				$this->view = 'file/image';
			break;
			case 'text/plain':
				$this->setTemplate('base');
				$this->view = 'file/text';
			break;
			default:
				$this->setTemplate('base');
				
			break;
		}
	}
	
	public function getContent() {
		if (!is_null($this->view)) $this->pick($this->view);
	}
	
}

?>