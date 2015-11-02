<?php

class Column extends BaseModel {

	public $Field;
	public $Type;
	public $Null;
	public $Key;
	public $Default;
	public $Extra;
	
	public function enum() {
		preg_match('/enum\((.*)\)$/', $this->Type, $matches);
		$vals = explode(',', $matches[1]);
		$trimmedvals = array();
		foreach($vals as $key => $value) {
			$value = trim($value, "'");
			$trimmedvals[] = $value;
		}
		return $trimmedvals;
	}
	
}

?>