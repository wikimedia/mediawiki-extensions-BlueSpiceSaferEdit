/**
 * SaferEdit extension
 *
 * Part of BlueSpice MediaWiki
 *
 * @author     Markus Glaser <glaser@hallowelt.com>
 * @package    Bluespice_Extensions
 * @subpackage SaferEdit
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

/**
 * Base class for all safer edit related methods and properties
 */
BsSaferEditEditMode = {
	/**
	 * Time between two intermediate saves
	 * @var integer time in seconds
	 */
	interval: mw.config.get( 'bsgSaferEditInterval' ) * 1000,
	/**
	 * Pointer to javascript timeout callback, needed to cancel timeout when changes are saved
	 * @var pointer javascript timeout callback
	 */
	timeout: false,
	/**
	 * Indicates if text was changed client side since the last user saving
	 * @var bool true if text was changed
	 */
	isUnsaved: false,
	/**
	 * Indicates whether page is in edit mode and saving of texts should be started
	 * @var bool true if page is in edit mode
	 */
	editMode: false,
	/**
	 * Initiates saving of edited text in certain intervals
	 */
	startSaving: function () {
		BSPing.registerListener(
			'SaferEditSave',
			0,
			[{
						section: mw.config.get( 'bsSaferEditEditSection' ),
						bUnsavedChanges: BsSaferEditEditMode.hasUnsavedChanges( )
			}],
			BsSaferEditEditMode.startSaving
		);
	},
	/**
	 * Conducts necessary preparations of edit form and starts intermediate saving
	 */
	init: function() {
		if ( mw.config.get( "wgAction" ) == "edit" || mw.config.get( "wgAction" ) == "submit" ) {
			BsSaferEditEditMode.editMode = true;
		}
		if ( mw.config.get( "wgCanonicalNamespace" ) == "Special" ) {
			BsSaferEditEditMode.editMode = false;
		}

		BsSaferEditEditMode.origText = BsSaferEditEditMode.getText();
		if ( !BsSaferEditEditMode.editMode ) {
			return;
		}
		BsSaferEditEditMode.startSaving();
	},
	hasUnsavedChanges: function ( mode ) {
		BsSaferEditEditMode.isUnsaved = BsSaferEditEditMode.editMode;
		return true;
	},
	onSavedText: function ( name ) {
		BsSaferEditEditMode.isUnsaved = false;
	},

	onBeforeToggleEditor: function ( name, data ) {
		BsSaferEditEditMode.hasUnsavedChanges( data );
	},
	getText: function ( mode ) {
		var text = '';

		switch ( mode ) {
			case "MW":
				text = $( '#wpTextbox1' ).val();
				break;
			case "VisualEditor":
				text = tinyMCE.activeEditor.getContent( { save: true } );
				break;
			default: //detect
				if ( typeof VisualEditorMode !== 'undefined' && VisualEditorMode ) {
					text = tinyMCE.activeEditor.getContent( { save: true } );
					break;
				}
				text = $( '#wpTextbox1' ).val();
		}

		return text || '';
	}
};

mw.loader.using( 'ext.bluespice', function() {
	BsSaferEditEditMode.init();
} );

$( document ).on( 'BSVisualEditorBeforeToggleEditor', BsSaferEditEditMode.onBeforeToggleEditor );
$( document ).on( 'BSVisualEditorSavedText', BsSaferEditEditMode.onSavedText );
