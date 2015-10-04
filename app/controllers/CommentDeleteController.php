<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../core/interfaces/IConfirm.php';

class CommentDeleteController extends BaseController implements IRedirect, IConfirm {
	
	private $comment;
	private $view;
	
	public function beforeRender() {
		$this->comment = hasGet('id') ? Comment::byId(get('id')) : null;
		
		if (is_null($this->comment)) return;
		
		if ($this->formValidate([])) {
			if ($this->comment->remove()) {
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_comment_delete'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getContent() {
		if (!is_null($this->view)) $this->pick($this->view);
	}
	
	public function getRedirect() {
		return new Redirect(View::str('order_delete_successfuly')); 
	}
	
	public function getConfirm() {
		if (is_null($this->comment)) return null;
		return new Redirect(View::str('you_sure_comment_delete'), Application::$routes->byName(Route::ORDER_VIEW)->path . '?id=' . $this->comment->oid);
	}
	
}

?>