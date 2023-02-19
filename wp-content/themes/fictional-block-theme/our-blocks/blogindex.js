wp.blocks.registerBlockType("ourblocktheme/blogindex", {
    title: "University Blog Index",
    edit: function () {
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Blog Index placeholder');
    },
    save: function () {
        return null;
    }
});