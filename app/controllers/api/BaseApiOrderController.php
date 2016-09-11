<?php

Util::inc('controllers', 'api/BaseApiController.php');

class BaseApiOrderController extends BaseApiController {
	
	public $order;
	
	protected function loadOrder($oid = null) {
		if (!hasPost('id') && is_null($oid)) {
			$this->addAlert(sprintf(View::str('not_found'), View::str('order')), 'danger');
			return false;
		}
		
		$id = is_null($oid) ? post('id') : $oid;
		
		$this->order = Order::byId($id, Account::getGid());
		
		if (!$this->order) {
			$this->addAlert(sprintf(View::str('not_found'), View::str('order')), 'danger');
			return false;
		}
		
		return true;
	}
	
	protected function save($files = null, $comment_id = 0) {
		$status = !is_null($this->order) && $this->dump($files, $comment_id);
		if (!$status) $this->addAlert(View::str('error_ftp_connect'), 'danger');
		return $status;
	}
	
	private function dump($files, $comment_id = 0) {
		if (Application::$ftp->connect()) {
			$this->order->addProperty('comments', Comment::getAll($this->order->id));
			$attachments = isset($files) ? reArrayFiles($files) : [];
			
			Application::$ftp->addXML($this->order);
			
			if (count($attachments)>0) {
				$a = Application::$ftp->addFiles($attachments, $this->order->gid, $this->order->id);
				
				//валидные файлы
				if (count($a[0])>0) {
					File::add($a[0], $this->order->id, $comment_id);
				}
				
				//фейлы
				if (count($a[1])>0) {
					foreach($a[1] as $file) {
						$this->addAlert(sprintf(View::str('error_file_attach'), $file->name), 'danger');
					}
				}
			}
			
			Application::$ftp->close();
			
			return true;
		}
		
		return false;
	}
	
}
?>