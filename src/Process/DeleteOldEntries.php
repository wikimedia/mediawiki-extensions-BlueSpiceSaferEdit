<?php

namespace BlueSpice\SaferEdit\Process;

use BlueSpice\SaferEdit\KeepAliveWatcher;
use MediaWiki\Logger\LoggerFactory;
use MWStake\MediaWiki\Component\ProcessManager\IProcessStep;
use Wikimedia\Rdbms\ILoadBalancer;

class DeleteOldEntries implements IProcessStep {

	/**
	 * @param ILoadBalancer $lb
	 */
	public function __construct(
		private readonly ILoadBalancer $lb
	) {
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function execute( $data = [] ): array {
		$watcher = new KeepAliveWatcher( $this->lb );
		$watcher->setLogger( LoggerFactory::getInstance( 'BlueSpiceSaferEdit' ) );
		$watcher->removeExpired();

		return [];
	}
}
