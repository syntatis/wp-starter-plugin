import { useBlockProps } from '@wordpress/block-editor';
import { useSettings } from '../../components/Settings';

export default function Edit() {
	const { getOption } = useSettings();
	return (
		<p { ...useBlockProps() }>
			{ getOption( 'wp_starter_plugin_greeting' ) }
		</p>
	);
}
