<?php

class EmployeeSearchCondition {

	const dname_COLUMN = "dname_0";
	private $department;
	private $job;
	private $dname;
	private $orderByString;
	
	public function getDepartment() {
		return $this->department;
	}

	public function setDepartment(Department2 $department = null) {
		$this->department = $department;
	}

	public function getDname() {
		return $this->dname;
	}

	public function setDname($dname) {
		$this->dname = $dname;
	}
    
	public function getJob() {
		return $this->job;
	}

	public function setJob($job) {
		$this->job = $job;
	}
	
	public function getOrderByString() {
		return $this->orderByString;
	}

	public function setOrderByString($orderByString) {
		$this->orderByString = $orderByString;
	}
}

?>