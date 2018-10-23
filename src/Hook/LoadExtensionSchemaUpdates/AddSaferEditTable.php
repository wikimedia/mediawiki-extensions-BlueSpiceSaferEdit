<?php

namespace BlueSpice\SaferEdit\Hook\LoadExtensionSchemaUpdates;

use BlueSpice\Hook\LoadExtensionSchemaUpdates;

class AddSaferEditTable extends LoadExtensionSchemaUpdates {

	protected function doProcess() {
		$dbType = $this->updater->getDB()->getType();
		$dir = "{$this->getExtensionPath()}/maintenance/db/$dbType";

		if ( $dbType == 'mysql' ) {
			$this->updater->addExtensionIndex(
				'bs_saferedit',
				'se_page_title',
				"$dir/bs_saferedit.patch.se_page_title.index.sql"
			);
			$this->updater->addExtensionIndex(
				'bs_saferedit',
				'se_page_namespace',
				"$dir/bs_saferedit.patch.se_page_namespace.index.sql"
			);
		}

		if ( $dbType == 'sqlite' ) {
			$dbType = 'mysql';
		}
		$this->updater->addExtensionTable(
			'bs_saferedit',
			"$dir/bs_saferedit.sql"
		);

		$this->updater->dropExtensionField(
			'bs_saferedit',
			'se_text',
			"$dir/bs_saferedit.patch.se_text.sql"
		);
	}

	protected function getExtensionPath() {
		return dirname( dirname( dirname( __DIR__ ) ) );
	}
}
