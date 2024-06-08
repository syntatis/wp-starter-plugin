import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { useState } from 'react';
import { getAllOptions } from '../../helpers/option';

const allOptions = getAllOptions();
const filterValues = ( values ) => {
	for ( const name in values ) {
		if ( ! Object.keys( allOptions ).includes( name ) ) {
			delete values[ name ];
		}
	}

	return values;
};

export const useSettings = () => {
	const [ values, setValues ] = useState( allOptions );
	const [ status, setStatus ] = useState();
	const [ statusText, setStatusText ] = useState();

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
		pluginName: window.__wpStarterPlugin?.pluginName,
		optionPrefix: window.__wpStarterPlugin?.optionPrefix,
	};
};
