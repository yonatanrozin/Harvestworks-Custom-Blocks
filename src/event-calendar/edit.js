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

import {useSelect} from "@wordpress/data"
import {PanelBody, ColorPicker} from "@wordpress/components"
import {InspectorControls} from "@wordpress/block-editor"

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({attributes, setAttributes}) {

	const events = useSelect(select => select("core").getEntityRecords("postType", "event"));

	return (
		<>
			<ul { ...useBlockProps() }>
				{events?.map(post => 
					<li>
						{post.title.raw}
					</li>
				)}
			</ul>

			<InspectorControls >
				<PanelBody title="Has event color">
					<ColorPicker color={attributes.has_event_color}
						onChange={color => setAttributes({has_event_color: color})}/>
				</PanelBody>
				<PanelBody title="Listed date color">
					<ColorPicker color={attributes.queried_day_color}
						onChange={color => setAttributes({queried_day_color: color})}/>				
				</PanelBody>
			</InspectorControls>
		</>
	);
}
