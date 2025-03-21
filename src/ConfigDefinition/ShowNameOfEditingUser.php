<?php

namespace BlueSpice\SaferEdit\ConfigDefinition;

use BlueSpice\ConfigDefinition\BooleanSetting;

class ShowNameOfEditingUser extends BooleanSetting {

	/**
	 * @inheritDoc
	 */
	public function getPaths() {
		return [
			static::MAIN_PATH_FEATURE . '/' . static::FEATURE_QUALITY_ASSURANCE . '/BlueSpiceSaferEdit',
			static::MAIN_PATH_EXTENSION . '/BlueSpiceSaferEdit/' . static::FEATURE_QUALITY_ASSURANCE,
			static::MAIN_PATH_PACKAGE . '/' . static::PACKAGE_FREE . '/BlueSpiceSaferEdit',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getLabelMessageKey() {
		return 'bs-saferedit-pref-show-editinguser';
	}

	/**
	 * @inheritDoc
	 */
	public function isRLConfigVar() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getHelpMessageKey() {
		return 'bs-saferedit-pref-show-editinguser-help';
	}
}
