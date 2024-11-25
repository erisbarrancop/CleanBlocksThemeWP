wp.blocks.registerBlockType("ourblocktheme/eventarchive", {
    title: "Event Archive",
    edit: function(){
        return wp.element.createElement("div", {className:"our-placeholder-block"}, "Event Archive")
    },
    save: function(){
        return null
    }
  })