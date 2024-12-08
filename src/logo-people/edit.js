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
	const url = window.location.origin + '/wp-content/uploads/2024/12/';
	const images = [
		'community',
		'visitor',
		'learning',
		'performance',
		'programs',
		'studio',
	];

	return (
		<div {...useBlockProps()}>
			<div className='people'>
				{
					images.length > 0 ?
						images.map((image, index) => {
							return (
								<img src={url + image + '.png'} alt={''} key={index} className={index === 0 ? 'active' : ''} />
							)
						}) :
						<img src="https://via.placeholder.com/150" alt="placeholder" />
				}
			</div>

			<div className='spacer'>

			</div>
		</div>
	);
}
