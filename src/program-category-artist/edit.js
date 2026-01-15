import { useBlockProps, InnerBlocks, BlockContextProvider } from '@wordpress/block-editor';

export default function Edit() {

	const blockProps = useBlockProps();

	return <div {...blockProps} >
		<BlockContextProvider value={{postType: "artist", postId: 0}}>
			<InnerBlocks />
		</BlockContextProvider>
	</div>
}