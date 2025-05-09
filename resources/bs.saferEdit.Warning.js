bs.util.registerNamespace( 'bs.saferEdit' );

bs.saferEdit.Warning = function ( cfg ) {
	cfg = cfg || {};
	this.interval = cfg.interval || 0;

	this.registerListener();
};

OO.initClass( bs.saferEdit.Warning );

bs.saferEdit.Warning.prototype.someoneIsEditingListener = function ( result, listener ) { // eslint-disable-line no-unused-vars
	if ( result.success !== true ) {
		this.registerListener();
		return;
	}

	this.updateUI( result );
	this.registerListener();
};

bs.saferEdit.Warning.prototype.registerListener = function () {
	BSPing.registerListener(
		'SaferEditIsSomeoneEditing',
		this.interval,
		[],
		this.someoneIsEditingListener.bind( this )
	);
};

bs.saferEdit.Warning.prototype.updateUI = function ( result ) {
	if ( !result.hasOwnProperty( 'someoneEditingView' ) || result.someoneEditingView === '' ) {
		bs.alerts.remove( 'bs-saferedit-warning' );
		return;
	}

	const $elem = $( '<div>' ).append( result.someoneEditingView );

	bs.alerts.add(
		'bs-saferedit-warning',
		$elem,
		bs.alerts.TYPE_WARNING
	);
};
