<?php

namespace BlueSpice\SaferEdit\Hook\EditPage_showEditForm_initial;

use MediaWiki\EditPage\EditPage;
use MediaWiki\Output\OutputPage;

class SetEditSection {
	/**
	 * @param EditPage $editPage
	 * @param OutputPage $output
	 * @return bool
	 */
	public static function callback( $editPage, $output ) {
		$output->addJsConfigVars(
			'bsSaferEditEditSection',
			$editPage->getContext()->getRequest()->getVal( 'section', -1 )
		);
		return true;
	}
}
