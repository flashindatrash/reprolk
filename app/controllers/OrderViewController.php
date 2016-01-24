<?php

include_once '../app/controllers/BaseOrderController.php';

class OrderViewController extends BaseOrderController {
	
	public $form;
	public $comments = [];
	public $main_attachments = [];
	public $comment_attachments = [];
	public $canComment = false;
	
	public function beforeRender() {
		$this->loadOrder();
		
		if (is_null($this->order)) return;
		
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
		
		$this->canComment = $this->order->canComment() && UserAccess::check(UserAccess::COMMENT_ADD);
		
		$this->form = $this->createForm('OrderView');
		$this->form->loadFields(Route::ORDER_ADD);
		$this->form->setSession(objectToArray($this->order));
		$this->form->setSession(array('pid' => $this->order->photopolymer_name, 'username' => View::link(Route::PROFILE, $this->order->username, 'id=' . $this->order->uid)));
		$this->view = 'order/view';
	}
	
	
	private function addComment() {
		if ($this->formValidate(['comment'])) {
			$cid = Comment::add($this->order->id, post('comment'), $this->order->status);
			$this->save($_FILES['files'], $cid);
		}
	}
	
}

?>