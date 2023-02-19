wp.blocks.registerBlockType("ourblocktheme/eventsandblogs", {
    title: "University Events and Blogs",
    edit: function () {
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Events and blogs placeholder');
    },
    save: function () {
        return null;
    }
});