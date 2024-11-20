import { registerBlockType } from "@wordpress/blocks"

registerBlockType("ourblocktheme/genericheading", {
    title: "Generic Heading",
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent(){
    return (
        <div>Hi!</div>
    )
}


function SaveComponent(){
    return (
        <div>This is our heading block.</div>
    )
}