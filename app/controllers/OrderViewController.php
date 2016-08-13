<?php
		
Util::inc('controllers', 'BaseOrderViewController.php');

class OrderViewController extends BaseOrderViewController {
	
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
		
		$this->include_fileinput();
		
		$this->form = $this->createForm('OrderView');
	}
	
	public function getContent() {
		if (is_null($this->order)) return;
		$this->pick('order/view');
		$this->pick('order/attachment');
		$this->pick('order/comment');
	}
	
	private function addComment() {
		if ($this->formValidate([Order::FIELD_COMMENT])) {
			$cid = Comment::add($this->order->id, post(Order::FIELD_COMMENT), $this->order->status);
			$this->save($_FILES['files'], $cid);
		}
	}
	
	protected function generateBreadcrump($items) {
		$li = array();
		
		if (!is_null($this->order)) {
			if ($this->order->canApprove()) $li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_APPROVAL, null, 'id=' . $this->order->id));
			if ($this->order->canEdit()) $li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_EDIT, null, 'id=' . $this->order->id));
			if ($this->order->canDuplicate()) $li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_DUPLICATE, null, 'id=' . $this->order->id));
			if ($this->order->canCancel()) $li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_CANCEL, null, 'id=' . $this->order->id));
			if ($this->order->canDelete()) $li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_DELETE, null, 'id=' . $this->order->id));
		}
	
		$menu = '';
		if (count($li)>0) {
			$menu = View::rightBreadcrumpDropdown(View::str('select_action'), $li);
		}
		
		return parent::generateBreadcrump($items) . $menu;
	}
}

?>