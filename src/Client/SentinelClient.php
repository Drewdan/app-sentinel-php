<?php

namespace Drewdan\CodeSentinel\Client;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class SentinelClient {

	protected PendingRequest $client;

	protected string $ingressUrl;

	protected string $applicationId;

	protected string $key;

	protected string $uri;

	public function __construct(string $ingressUrl, string $applicationId, string $key) {
		$this->ingressUrl = $ingressUrl;
		$this->applicationId = $applicationId;
		$this->key = $key;
		$this->uri = $this->ingressUrl . '/api/v1/' . $this->applicationId . '/events';
		$this->client = Http::withToken($this->key)->asJson()->acceptJson();
	}

	/**
	 * @throws \Illuminate\Http\Client\RequestException
	 */
	public function postLog(string $name, string $type = 'Info') {
		$body = [
			'name' => $name,
			'type' => $type,
		];

		$this->client->post($this->uri, $body)->throw();
	}
}
