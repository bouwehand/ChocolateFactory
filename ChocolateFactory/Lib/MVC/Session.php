<?php
class ChocolateFactory_MVC_Session {

	private $sessionId;
	private $ip;
	private $user;
	private $timestamp;

	public function setSessionId($sessionId) {
	  $this->sessionId = $sessionId;
		return $this;
	}

	public function setIp($ip) {
	  $this->ip = $ip;
		return $this;
	}

	public function setUser(User $user){
	  $this->user = $user;
		return $this;

	}

	public function setTimestamp($timestamp){
	  $this->timestamp = $timestamp;
		return $this;
	}

	public function save(){

		return true;
	}

}