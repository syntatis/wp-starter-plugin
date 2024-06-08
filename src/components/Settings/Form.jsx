import {
	Button,
	Notice,
	Spinner,
	TextField,
} from '@syntatis/wp-classic-components';
import { __ } from '@wordpress/i18n';
import { useSettings } from './useSettings';
import { getOption } from '../../helpers/option';
import '@syntatis/wp-classic-components/dist/index.css';

export const Form = () => {
	const settings = useSettings();

	if ( ! settings.values ) {
		return;
	}

	const { status, statusText, updateStatus, updateValues } = settings;
	const isUpdating = status === 'updating';

	return (
		<>
			{ ! isUpdating && status && statusText && (
				<Notice
					isDismissable
					level={ status }
					onDismiss={ () => updateStatus( null ) }
				>
					{ statusText }
				</Notice>
			) }
			<form
				method="POST"
				onSubmit={ ( event ) => {
					event.preventDefault();
					updateStatus( null );
					updateValues( new FormData( event.target ) );
				} }
			>
				<fieldset disabled={ isUpdating }>
					<table className="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row">
									<label
										htmlFor="wp-starter-plugin-settings-greeting"
										id="wp-starter-plugin-settings-greeting-label"
									>
										{ __(
											'Greeting',
											'wp-starter-plugin'
										) }
									</label>
								</th>
								<td>
									<TextField
										aria-labelledby="wp-starter-plugin-settings-greeting-label"
										id="wp-starter-plugin-settings-greeting"
										className="regular-text"
										defaultValue={ getOption(
											'wp_starter_plugin_greeting'
										) }
										name="wp_starter_plugin_greeting"
									/>
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<div className="submit">
					<Button
						isDisabled={ isUpdating }
						prefix={ isUpdating && <Spinner /> }
						type="submit"
					>
						{ isUpdating
							? __( 'Updating Settings', 'wp-starter-plugin' )
							: __( 'Update Settings', 'wp-starter-plugin' ) }
					</Button>
				</div>
			</form>
		</>
	);
};
