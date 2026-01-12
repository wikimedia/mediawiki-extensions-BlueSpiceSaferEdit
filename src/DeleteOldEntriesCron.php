<?php

namespace BlueSpice\SaferEdit;

use BlueSpice\SaferEdit\Process\DeleteOldEntries;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\ProcessManager\ManagedProcess;
use MWStake\MediaWiki\Component\WikiCron\WikiCronManager;

class DeleteOldEntriesCron {

	/**
	 * @return void
	 */
	public static function register(): void {
		if ( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_QUIBBLE_CI' ) ) {
			return;
		}

		/** @var WikiCronManager $cronManager */
		$cronManager = MediaWikiServices::getInstance()->getService( 'MWStake.WikiCronManager' );

		// Interval: Every 5 Minutes. See KeepAliveWatcher::DEFAULT_EXPIRY_TIME
		$cronManager->registerCron( 'bs-saferedit-delete-old-entries', '*/5 * * * *', new ManagedProcess( [
			'delete-old-entries' => [
				'class' => DeleteOldEntries::class,
				'services' => [
					'DBLoadBalancer',
				],
			]
		] ) );
	}
}
