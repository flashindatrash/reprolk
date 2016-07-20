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
	
	public function close() {
		if ($this->connection) ftp_close($this->connection);
	}
	
	public function addXML($order) {
		$this->gotoDir($this->config['dir_orders']);
		
		include_once '../core/objects/XmlConstruct.php';
		
		$xml = new XmlConstruct('order');
		$xml->fromArray($order->toArray());
		
		$tmp = tmpfile();
		fwrite($tmp, $xml->getDocument());
		rewind($tmp);
		
		$name = $order->id . '_' . $order->gid . '.xml';
		
		ftp_fput($this->connection, $name, $tmp, FTP_ASCII);
		
		$this->gotoPrev(1);
	}
	
	public function getFile($file) {
		$this->gotoDir($this->config['dir_files']);
		$this->gotoDir($file->gid);
		$this->gotoDir($file->oid);
		if ($file->isComment()) $this->gotoDir($file->cid);
		
		$tmp = tmpfile();
		
		if (@ftp_fget($this->connection, $tmp, $file->name, FTP_ASCII, 0)) {
			return $tmp;
		}
		
		return null;
	}
	
	public function addFiles($files, $gid, $oid) {
		$this->gotoDir($this->config['dir_files']);
		$this->gotoDir($gid);
		$this->gotoDir($oid);
		
		$a = array();
		$b = array();
		
		foreach ($files as $f) {
			$file = new File();
			$file->name = $f["name"];
			$file->size = $f["size"];
			$file->type = $f["type"];
			
			if (!$file->type) { //файлы без типа вообще игноировать, хз откуда они вылазят
				continue;
			}
			
			if ($f["error"]!=0 || $file->size==0) {
				$b[] = $file;
				continue;
			}
			
			$stream = fopen($f["tmp_name"], 'r');
			
			ftp_fput($this->connection, translit($file->name), $stream, FTP_BINARY);
			
			fseek($stream, 0);
			$content = fread($stream, $file->size);
			$file->content = mysql_real_escape_string($content);
			fclose($stream);
			$a[] = $file;
		}
		
		//возвращает имена файлов для последующего добавления в БД, собственно здесь можно их и переименовать...
		return [$a, $b];
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