wp.blocks.registerBlockType("ourblocktheme/singleevent", {
    title: "Single Event",
    edit: function(){
        return wp.element.createElement("div", {className:"our-placeholder-block"}, "Single Event")
    },
    save: function(){
        return null
    }
  })