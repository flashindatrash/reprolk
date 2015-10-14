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
			$this->gotoDir($this->config['dir_root']);
			return true;
		}
		return false;
	}
	
	public function addXML($order) {
		$this->gotoDir($this->config['dir_orders']);
		
		include_once '../core/objects/XmlConstruct.php';
		
		$xml = new XmlConstruct('order');
		$xml->fromArray($order->toArray());
		
		$file = tmpfile();
		fwrite($file, $xml->getDocument());
		rewind($file);
		
		$name = $order->id . '_' . $order->gid . '.xml';
		
		ftp_fput($this->connection, $name, $file, FTP_ASCII);
		
		$this->gotoPrev(1);
	}
	
	public function addFiles($files, $gid, $oid) {
		$this->gotoDir($this->config['dir_files']);
		$this->gotoDir($gid);
		$this->gotoDir($oid);
		
		$names = array();
		
		foreach ($files as $file) {
			$file_name = $file["name"];
			$file_tmp = $file["tmp_name"];
			$file_type = $file["type"];
			$file_size = $file["size"];
			$file_error = $file["error"];
			
			$file_stream = fopen($file_tmp, 'r');
			
			ftp_fput($this->connection, $file_name, $file_stream, FTP_ASCII);
			
			$names[] = $file_name;
		}
		
		//возвращает имена файлов для последующего добавления в БД, собственно здесь можно их и переименовать...
		return $names;
	}
	
	private function gotoDir($name) {
		$path = explode('/', $name);
		if (count($path)>1) {
			foreach ($path as $name) {
				$this->gotoDir($name);
			}
			return;
		}
		
		if (!@ftp_chdir($this->connection, $name)) {
			ftp_mkdir($this->connection, $name);
			ftp_chdir($this->connection, $name);
		}
	}
	
	private function gotoPrev($count = 1) {
		for ($i = 0; $i < $count; $i++) { 
			ftp_cdup($this->connection);
		}
	}
	
}

?>