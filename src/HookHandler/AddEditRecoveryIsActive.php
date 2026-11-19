<?php

namespace BlueSpice\SaferEdit\HookHandler;

use BlueSpice\SaferEdit\SaferEditManager;
use MediaWiki\Title\TitleFactory;
use MWStake\MediaWiki\Component\CommonWebAPIs\Hook\MWStakeCommonWebAPIsQueryStoreResultHook;

class AddEditRecoveryIsActive implements MWStakeCommonWebAPIsQueryStoreResultHook {

	/**
	 * @param SaferEditManager $saferEditManager
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		private readonly SaferEditManager $saferEditManager,
		private readonly TitleFactory $titleFactory
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function onMWStakeCommonWebAPIsQueryStoreResult( $store, &$result ) {
		if ( $store->getPath() !== '/editrecovery/list' ) {
			return;
		}

		foreach ( $result->getRecords() as $record ) {
			$title = $this->titleFactory->makeTitleSafe(
				(int)$record->get( 'er_namespace' ),
				(string)$record->get( 'er_title' )
			);
			if ( !$title ) {
				$record->set( 'is_active', -1 );
				continue;
			}

			$record->set( 'is_active', $this->saferEditManager->isPageBeingEdited( $title ) ? 1 : -1 );
		}
	}
}
