<?php

namespace app\core;

class Time{

	public $date;

	public function __construct(){
		$this->date = new \DateTime();
	}

	public function add($s){
		return $this->date->modify("+$s seconds");
	}

	public function addMinutes($m){
		return $this->date->modify("+$m minutes");
	}

	public function addHours($h){
		return $this->date->modify("+$h hours");
	}

	public function format($format){
		return $this->date->format("$format");
	}

}