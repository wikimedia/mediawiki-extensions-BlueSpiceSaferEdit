<?php

namespace BlueSpice\SaferEdit\Hook\BsAdapterAjaxPingResult;

use BlueSpice\Hook\BsAdapterAjaxPingResult;
use Title;
use Html;

class HandleSaferEditIsSomeoneEditing extends BsAdapterAjaxPingResult {

	/**
	 *
	 * @var Title
	 */
	protected $title = null;

	protected function skipProcessing() {
		$this->title = Title::newFromText( $this->titleText );
		if( $this->title === null ) {
			return true;
		}

		return $this->reference !== 'SaferEditIsSomeoneEditing';
	}

	protected function doProcess() {
		$this->singleResults['success'] = true;

		$intermediateEdits = $this->getIntermediateEdits( $this->title );
		if ( empty( $intermediateEdits ) ) {
			return true;
		}

		$this->singleResults['someoneEditingView'] = '';
		$this->singleResults['safereditView'] = '';

		$interval = $this->getInterval();
		$thresholdTS = wfTimestamp( TS_MW, time() - $interval );
		$currentUserName = $this->getContext()->getUser()->getName();

		foreach ( $intermediateEdits as $row ) {
			if( $row->se_user_name === $currentUserName ) {
				continue;
			}

			if( $row->se_timestamp < $thresholdTS ) {
				continue;
			}

			$html = $this->makeAlertHtml( $row->se_user_name );

			$this->singleResults['someoneEditingView'] = $html;
		}
		return true;
	}

	protected $intermediateEdits = null;

	/**
	 * Loads intermediate edits
	 * @param Title $title
	 * @return array
	 */
	protected function getIntermediateEdits( $title ) {
		if ( is_array( $this->intermediateEdits ) ) {
			return $this->intermediateEdits;
		}

		if ( is_null( $title ) || !$title->exists() ) {
			return $this->intermediateEdits = [];
		}

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			'bs_saferedit',
			'*',
			[
				"se_page_title" => $title->getDBkey(),
				"se_page_namespace" => $title->getNamespace(),
			],
			__METHOD__,
			[ "ORDER BY" => "se_id DESC" ]
		);

		foreach( $res as $row ) {
			$this->intermediateEdits[] = $row;
		}

		return $this->intermediateEdits;
	}

	protected function getInterval() {
		$saferEditInterval = $this->getConfig()->get( 'SaferEditInterval' );
		$pingInterval = $this->getConfig()->get( 'PingInterval' );

		//HINT PW from the ancient times: +1 secound response time is enough
		return $saferEditInterval + $pingInterval + 1;
	}

	protected function makeAlertHtml( $userName ) {
		$showName = $this->getConfig()->get( 'SaferEditShowNameOfEditingUser' );

		$message = wfMessage( 'bs-saferedit-someone-editing' );
		if( $showName ) {
			$message = wfMessage( 'bs-saferedit-user-editing', $userName );
		}

		return Html::element( 'div', [], $message->text() );
	}
}