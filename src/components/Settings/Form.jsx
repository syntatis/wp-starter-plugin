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
	const {
		optionPrefix,
		pluginName,
		status,
		statusText,
		updateStatus,
		updateValues,
		values,
	} = useSettings();

	if ( ! values ) {
		return;
	}

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
								<th
									id={ `${ pluginName }-settings-greeting` }
									scope="row"
								>
									{ __( 'Greeting', 'wp-starter-plugin' ) }
								</th>
								<td>
									<TextField
										aria-labelledby={ `${ pluginName }-settings-greeting` }
										className="regular-text"
										defaultValue={ getOption(
											`${ optionPrefix }greeting`
										) }
										name={ `${ optionPrefix }greeting` }
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
