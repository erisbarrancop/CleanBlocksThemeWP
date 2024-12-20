import { useBlockProps, InnerBlocks } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"

export default function Edit(props) {
  const blockProps = useBlockProps()
  return (
    <div {...blockProps}>
    <div style={{ backgroundColor: "#333", padding: "35px" }}>
            <p style={{ textAlign: "center", fontSize: "20px", color:"#FFF" }}>Slideshow</p>
            <InnerBlocks allowedBlocks={["ourblocktheme/slide"]}/>
        </div>
    </div>
  )
}
