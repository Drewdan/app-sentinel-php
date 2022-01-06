<?php

namespace Drewdan\AppSentinel\Handler;

use Monolog\Logger;
use Illuminate\Support\Str;
use Drewdan\AppSentinel\LogEntry;
use Monolog\Handler\AbstractProcessingHandler;
use Drewdan\AppSentinel\Client\SentinelClient;

class SentinelHandler extends AbstractProcessingHandler {

	protected $client;

	public function __construct($level = Logger::DEBUG, bool $bubble = true) {
		parent::__construct($level, $bubble);

		$this->client = resolve(SentinelClient::class);
	}

	protected function write(array $record): void {
		if (Str::startsWith($record['message'], 'Received a payload from client')) {
			return;
		}

		$logEntry = new LogEntry($record);

		if (config('sentinel.user.retrieve')) {
			$logEntry->addUserToLog();
		}

		$this->client->postLog($logEntry);
	}
}
