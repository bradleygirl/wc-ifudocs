/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {
	// Attributes for the block wrapper
	const blockProps = useBlockProps()

	// Determine the curent post type in the editor context
	const currentPostType = useSelect((select) => {
		return select('core/editor').getCurrentPostType()
	}, [])

	// Fetch the meta as an object and the setMeta function
	const [meta, setMeta] = useEntityProp('postType', currentPostType, 'meta');

	// Flexible helper for setting a single meta value w/o mutating state
	const updateMeta = ( key, value ) => {
		setMeta( { ...meta, [key]: value } );
	};

	return (
		<p {...blockProps}>
			{__('Wc Ifudocs – hello from the editor!', 'wcifu-docs')}
		</p>
	);
}
