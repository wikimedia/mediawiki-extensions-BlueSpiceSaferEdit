<?php

namespace BlueSpice\SaferEdit\Privacy;

use BlueSpice\Privacy\IPrivacyHandler;

class Handler implements IPrivacyHandler {
	protected $user;
	protected $db;

	public function __construct( \User $user, \Database $db ) {
		$this->user = $user;
		$this->db = $db;
	}

	public function anonymize( $newUsername ) {
		$this->db->update(
			'bs_saferedit',
			[ 'se_user_name' => $newUsername ],
			[ 'se_user_name' => $this->user->getName() ]
		);

		return \Status::newGood();
	}
}