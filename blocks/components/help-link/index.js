/**
* WordPress dependencies
*/
import { __ } from '@wordpress/i18n'

/**
 * Render Help link
*/

const GiveHelpLink = () => {
	return (
		<p className="give-blank-slate__help">
			Brauchen Sie Hilfe? Beginnen Sie mit <a href="http://docs.#.com/give101/" target="_blank" rel="noopener noreferrer">{ __( 'Beste Spende 101' ) }</a>
		</p>
	);
};

export default GiveHelpLink;
