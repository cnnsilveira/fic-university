wp.blocks.registerBlockType("ourblocktheme/mynotes", {
    title: "University My notes",
    edit: function () {
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'My notes placeholder');
    },
    save: function () {
        return null;
    }
});