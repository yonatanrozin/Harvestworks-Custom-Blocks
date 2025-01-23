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

import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { useEffect, useState } from 'react';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { supplementaryLinks } = attributes;

	const [pages, setPages] = useState([]);

	const pageID = window.location.href.split('postId=')[1].split('&')[0] ?? undefined;

	useEffect(() => {
		if (!pageID) {
			return;
		}

		const fetchFirstAncestor = async (id) => {
			const data = await fetch('/index.php/wp-json/harvestworks/v1/ancestor?id=' + pageID)
				.then(response => response.json()).catch(error => console.error('Error fetching pages:', error));

			console.log('Fetched ancestor:', data);
			return data;
		}

		const fetchChildren = async (id, title, link) => {
			if (id === 0 || !id || !title || !link || link === '') {
				return <>
					<li><a>Navigation</a></li>
					<ul>
						<li>Example</li>
						<li>Navigation</li>
						<ul>
							<li>Hierarchy</li>
						</ul>
						<li>Links</li>
						<li>Shown Because</li>
						<ul>
							<li>There Is No</li>
							<li>Current</li>
							<li>Parent Page</li>
						</ul>
						<li>For The One</li>
						<li>You&apos;re Editing</li>
					</ul>
				</>
			}

			const pageList = [];

			console.log('Fetching children for page ID:', id);
			pageList.push((
				<li key={id ?? ''}>
					<a href={link ?? ''}>{title ?? ''}</a>
				</li>
			));

			const data = await fetch('/index.php/wp-json/harvestworks/v1/pages?id=' + id)
				.then(response => response.json()).catch(error => console.error('Error fetching pages:', error));

			console.log('Fetched pages:', data);

			const subPageList = await Promise.all(data.map(async (element) => {
				return fetchChildren(element.id, element.title, element.link);
			}));

			pageList.push(
				<ul key={id + 'sub' ?? ''}>
					{subPageList}
				</ul>
			);

			console.log('Rendering pages:', pageList);

			return pageList;
		}

		fetchFirstAncestor(pageID).then((ancestor) => {
			console.log('Starting recursion: ', ancestor);
			fetchChildren(ancestor.id, ancestor.title, ancestor.link).then(pageList => setPages(pageList))
		}
		);

	}, [setPages]);


	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'navigation-sidebar')}>
					<TextareaControl
						__nextHasNoMarginBottom
						label={__(
							"Supplementary Links",
							'navigation-sidebar'
						)}
						help="Enter extra links to include in the sidebar, in the following rule format. ex. Parent Page Title <-> Link Name <-> http://harvestworks.org/special-link"
						value={supplementaryLinks || ''}
						onChange={(value) => setAttributes({ supplementaryLinks: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				<div className='sidebar'>
					<ul>
						{pages}
					</ul>
				</div>
			</div >
		</>
	);
}
