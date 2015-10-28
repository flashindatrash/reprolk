<?php

include_once '../app/controllers/BaseOrderController.php';

class OrderViewController extends BaseOrderController {
	
	public $comments = [];
	public $main_attachments = [];
	public $comment_attachments = [];
	public $canComment;
	
	public function beforeRender() {
		$this->loadOrder();
		
		if (!is_null($this->order)) {
			//добавим коммент, если есть
			$this->addComment();
			
			//загрузим комменты
			$this->comments = Comment::getAll($this->order->id);
			
			//загрузим файлы
			$files = File::getAll($this->order->id);
			foreach ($files as $file) {
				if ($file->isComment()) {
					if (!isset($this->comment_attachments[$file->cid])) $this->comment_attachments[$file->cid] = array();
					$this->comment_attachments[$file->cid][] = $file;
				} else $this->main_attachments[] = $file;
			}
		}
		
		$this->canComment = $this->order->canComment() && UserAccess::check(UserAccess::COMMENT_ADD);
	}
	
	public function getContent() {
		if ($this->order) $this->pick('order/view');
	}
	
	private function addComment() {
		if ($this->formValidate(['comment'])) {
			$comment_id = Comment::add($this->order->id, post('comment'));
			$this->save($_FILES['files'], $comment_id);
		}
	}
	
}

?>