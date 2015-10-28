<?php

class BaseOrderController extends BaseController {
	
	public $order;
	
	protected function loadOrder() {
		if (!hasGet('id')) {
			$this->addAlert(View::str('error_order_not_found'), 'danger');
			return false;
		}
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		$id = get('id');
		$this->order = Order::byId($id, $gid);
		
		if (!$this->order) {
			$this->addAlert(View::str('error_order_not_found'), 'danger');
			return false;
		}
		
		return true;
	}
	
	protected function save($files = null, $comment_id = 0) {
		$status = !is_null($this->order) && static::dump($this->order, $files, $comment_id);
		if (!$status) $this->addAlert(View::str('error_ftp_connect'), 'danger');
		return $status;
	}
	
	public static function dump($order, $files, $comment_id = 0) {
		if (Application::$ftp->connect()) {
			$order->comments = Comment::getAll($order->id);
			$attachments = isset($files) ? reArrayFiles($files) : [];
			
			Application::$ftp->addXML($order);
			
			if (count($attachments)>0) {
				File::add(
					Application::$ftp->addFiles($attachments, $order->gid, $order->id), 
					$order->id,
					$comment_id
				);
			}
			
			Application::$ftp->close();
			
			return true;
		}
		
		return false;
	}
	
}