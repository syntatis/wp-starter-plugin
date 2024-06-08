import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { useState } from 'react';

export const useSettings = () => {
	const allOptions = window.__wpStarterPluginSettings?.options;
	const [ values, setValues ] = useState( allOptions );
	const [ status, setStatus ] = useState();
	const [ statusText, setStatusText ] = useState();
	const filterValues = ( v ) => {
		for ( const name in v ) {
			if ( ! Object.keys( allOptions ).includes( name ) ) {
				delete v[ name ];
			}
		}

		return v;
	};

	/**
	 * @param {string} name The option name.
	 */
	function getOption( name ) {
		return allOptions[ name ];
	}

	const updateValues = ( newValues ) => {
		setStatus( 'updating' );
		apiFetch( {
			path: '/wp/v2/settings',
			method: 'POST',
			data: newValues,
		} )
			.then( ( response ) => {
				setValues( filterValues( response ) );
			} )
			.catch( () => {
				setStatusText(
					__( 'Error updating settings.', 'wp-starter-plugin' )
				);
				setStatus( 'error' );
			} )
			.then( () => {
				setStatusText( __( 'Settings updated.', 'wp-starter-plugin' ) );
				setStatus( 'success' );
			} );
	};

	return {
		values,
		status,
		statusText,
		updateValues( formData ) {
			const newValues = {};
			for ( const entry of formData.entries() ) {
				if ( Object.keys( allOptions ).includes( entry[ 0 ] ) ) {
					newValues[ entry[ 0 ] ] = entry[ 1 ];
				}
			}
			updateValues( newValues );
		},
		updateStatus: setStatus,
		getOption,
	};
};
