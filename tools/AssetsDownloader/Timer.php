<?php

namespace towa0131\deresute\tools\AssetsDownloader;

class Timer {

	private $time = 0;

	public function __construct(int $time) {
		$this->time = $time;
	}

	public function diff(int $time) {
		return $time - $this->time;
	}

	public function set(int $time) {
		$this->time = $time;
	}

	public function get() {
		return $this->time;
	}

	public function next() {
		$this->time++;
	}

	public function prev() {
		$this->time--;
	}

	public function start() {
		// TODO
	}

	public function stop() {
		// TODO
	}
	
}
