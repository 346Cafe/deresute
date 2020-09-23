<?php

namespace towa0131\deresute\tools\AssetsDownloader;

class Timer
{
	private $time = 0;

	public function __construct(int $time)
	{
		$this->time = $time;
	}

	public function diff(int $time): int
	{
		return $time - $this->time;
	}

	public function set(int $time): void
	{
		$this->time = $time;
	}

	public function get(): int
	{
		return $this->time;
	}

	public function next(): void
	{
		$this->time++;
	}

	public function prev(): void
	{
		$this->time--;
	}

	public function start(): void
	{
		// TODO
	}

	public function stop(): void
	{
		// TODO
	}
}
