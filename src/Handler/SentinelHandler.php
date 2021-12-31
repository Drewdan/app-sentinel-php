<?php

namespace Drewdan\CodeSentinel\Handler;

use Monolog\Logger;
use Drewdan\CodeSentinel\LogEntry;
use Monolog\Handler\AbstractProcessingHandler;
use Drewdan\CodeSentinel\Client\SentinelClient;

class SentinelHandler extends AbstractProcessingHandler {

	protected $client;

	public function __construct($level = Logger::DEBUG, bool $bubble = true) {
		parent::__construct($level, $bubble);

		$this->client = resolve(SentinelClient::class);

	}

	/**
	 * @inheritDoc
	 */
	protected function write(array $record): void {
		$logEntry = new LogEntry($record);

		$this->client->postLog($logEntry->message, $logEntry->type);
	}
}
