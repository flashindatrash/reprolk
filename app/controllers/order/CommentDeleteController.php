<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IConfirm.php');
Util::inc('interfaces', 'IRedirect.php');

class CommentDeleteController extends WebController implements IRedirect, IConfirm {
	
	private $comment;
	
	public function beforeRender() {
		$this->comment = hasGet('id') ? Comment::byId(get('id')) : null;
		
		if (is_null($this->comment)) return;
		else if ($this->comment->isEditExpired()) {
			$this->addAlert(View::str('error_comment_expired'), 'danger');
			return;
		}
		
		if ($this->formValidate([])) {
			if ($this->comment->remove()) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_comment_delete'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('order_delete_successfuly'), Application::$routes->byName(Route::ORDER_VIEW)->path . '?id=' . $this->comment->oid . '#comments'); 
	}
	
	public function getConfirm() {
		return View::str('you_sure_comment_delete');
	}
	
}

?>