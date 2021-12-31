<?php

namespace Drewdan\CodeSentinel;

class LogEntry {

	public string $message;

	public string $type;

	public $date;

	public $stack;

	public $exception;

	public function __construct(array $data) {
		$this->message = $data['message'];
		$this->type = ucfirst(strtolower($data['level_name']));
		$this->date = $data['datetime'];
	}
}
