wp.blocks.registerBlockType("ourblocktheme/singlepost", {
    title: "Single Post",
    edit: function(){
        return wp.element.createElement("div", {className:"our-placeholder-block"}, "Single Post")
    },
    save: function(){
        return null
    }
  })