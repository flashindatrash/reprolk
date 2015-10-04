<?php

include_once '../app/controllers/BaseOrderController.php';

class OrderViewController extends BaseOrderController {
	
	public $comments = [];
	public $commented;
	
	public function beforeRender() {
		$this->loadOrder();
		
		if (!is_null($this->order)) {
			//добавим коммент, если есть
			if ($this->formValidate(['comment'])) {
				Comment::add($this->order->id, post('comment'));
			}
			
			//загрузим комменты
			$this->comments = Comment::getAll($this->order->id);
		}
		
		$this->commented = UserAccess::check(UserAccess::COMMENT_ADD);
	}
	
	public function getContent() {
		if ($this->order) $this->pick('order/view');
	}
	
}

?>