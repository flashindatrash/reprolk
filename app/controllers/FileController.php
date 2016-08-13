<?php

Util::inc('controllers', 'base/WebController.php');

class FileController extends WebController {
	
	public $file;
	public $view;
	public $content;
	
	public function beforeRender() {
		if (!hasGet('id')) return;
		
		$this->file = File::byId(get('id'));
		
		if (is_null($this->file) || !$this->file->isValid()) {
			$this->setTemplate('base');
			$filename = is_null($this->file) ? '' : $this->file->name;
			$this->addAlert(sprintf(View::str('error_file_deleted'), $filename), 'danger');
			return;
		}
		
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
				$this->setTemplate('download');
				$this->view = 'file/binary';
			break;
		}
	}
	
	public function getSize() {
		return $this->file->size;
	}
	
	public function getName() {
		return $this->file->name;
	}
	
	public function getType() {
		return $this->file->type;
	}
	
	public function getContent() {
		if (!is_null($this->view)) $this->pick($this->view);
	}
	
}