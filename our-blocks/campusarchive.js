wp.blocks.registerBlockType("ourblocktheme/campusarchive", {
    title: "Campus Archive",
    edit: function(){
        return wp.element.createElement("div", {className:"our-placeholder-block"}, "Campus Archive")
    },
    save: function(){
        return null
    }
  })