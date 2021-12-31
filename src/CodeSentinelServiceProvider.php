<?php

namespace Drewdan\CodeSentinel;

use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Drewdan\CodeSentinel\Client\SentinelClient;
use Drewdan\CodeSentinel\Handler\SentinelHandler;

class CodeSentinelServiceProvider extends ServiceProvider {

	public function register() {
		$this->mergeConfigFrom(__DIR__ . '/../config/sentinel.php', 'sentinel');

		$this->registerLogger();

		$this->app->bind(SentinelClient::class, function ($app) {
			return new SentinelClient(
				config('sentinel.ingress_url'),
				config('sentinel.application_id'),
				config('sentinel.key'),
			);
		});
	}

	public function boot() {
		if ($this->app->runningInConsole()) {

			$this->publishes([
				__DIR__ . '/../config/sentinel.php' => config_path('sentinel.php'),
			], 'config');

		}
	}

	public function registerLogger() {
		$this->app->singleton('sentinel.logger', function ($app) {
			//this should be the custom handler extending the abstract processing handler
			$handler = new SentinelHandler();
//
//            $logLevelString = config('logging.channels.flare.level', 'error');
//
//            $logLevel = $this->getLogLevel($logLevelString);
//
//            $handler->setMinimumReportLogLevel($logLevel);

			$logger = new Logger('Sentinel');
			$logger->pushHandler($handler);

			return $logger;
		});

		Log::extend('sentinel', function ($app) {
			return $app['sentinel.logger'];
		});
	}
}
