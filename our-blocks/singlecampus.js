vwp.blocks.registerBlockType("ourblocktheme/singlecampus", {
    title: "Single Campus",
    edit: function(){
        return wp.element.createElement("div", {className:"our-placeholder-block"}, "Single Campus")
    },
    save: function(){
        return null
    }
  })