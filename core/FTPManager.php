<?php

class FTPManager {
	
	private $config;
	private $connection;
	
	public function __construct($config) {
		$this->config = $config;
	}
	
	public function connect() {
		$this->connection = ftp_connect($this->config['host'], $this->config['port']);
		if ($this->connection && @ftp_login($this->connection, $this->config['username'], $this->config['password'])) {
			ftp_pasv($this->connection, toBool($this->config['passive_mode']));
			return true;
		}
		return false;
	}
	
	public function putOrder($order, $files) {
		$this->addXML($order);
		
		foreach ($files as $file) {
			$file_name = $file["name"];
			$file_tmp = $file["tmp_name"];
			$file_type = $file["type"];
			$file_size = $file["size"];
			$file_error = $file["error"];
		}
	}
	
	private function addXML($order) {
		if (!@ftp_chdir($this->connection, $this->config['dir_orders'])) {
			ftp_mkdir($this->connection, $this->config['dir_orders']);
			ftp_chdir($this->connection, $this->config['dir_orders']);
		}
		
		include_once '../core/objects/XmlConstruct.php';
		
		$xml = new XmlConstruct('order');
		$xml->fromArray($order->toArray());
		
		$file = tmpfile();
		fwrite($file, $xml->getDocument());
		rewind($file);
		
		$name = 'order_' . $order->id . '.xml';
		
		ftp_fput($this->connection, $name, $file, FTP_ASCII);
	}
	
}

?>