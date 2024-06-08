export function getAllOptions() {
	return window.__wpStarterPluginSettings?.options;
}

/**
 * @param {string} name The option name.
 */
export function getOption( name ) {
	return getAllOptions()[ name ];
}
