<?php

class IdentityTable {

    const TABLE = "IDENTITYTABLE";
	const myid_ID = "identity";
	const myid_COLUMN = "id";

	private $myid;
	
	private $idName;
	
	public function getMyid() {
		return $this->myid;
	}
	
	public function setMyid($myid) {
		$this->myid = $myid;
	}
	
	public function getIdName() {
		return $this->idName;
	}
	
	public function setIdName($idName) {
		$this->idName = $idName;
	}
}
?>