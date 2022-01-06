<?php

namespace Drewdan\CodeSentinel\Handler;

use Monolog\Logger;
use Illuminate\Support\Str;
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
	 * @throws \Illuminate\Http\Client\RequestException
	 */
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
