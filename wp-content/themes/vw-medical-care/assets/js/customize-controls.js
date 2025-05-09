( function( api ) {

	// Extends our custom "vw-medical-care" section.
	api.sectionConstructor['vw-medical-care'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );