/*jslint browser:true, eqeq:true*/
/*global define, window*/
define([
    'oroui/js/mediator',
    'oroui/js/app/views/base/page-region-view'
], function (mediator, PageRegionView) {
    'use strict';

    var BookmarkButtonView, document;

    document = window.document;

    BookmarkButtonView = PageRegionView.extend({
        pageItems: ['navigationElements', 'titleShort', 'titleSerialized'],

        /**
         * Type of element will be used to check that this navigation element enabled for current page. Data comes
         * from backend generated by NavigationElementsContentProvider
         *
         * @type string
         */
        navigationElementType: null,

        events: {
            'click': 'onToggle'
        },

        listen: {
            'add collection': 'updateState',
            'remove collection': 'updateState',
            'reset collection': 'updateState'
        },

        initialize: function(options){
            if (!options.navigationElementType) {
                throw new Error('"navigationItemElementType" is required option for bookmark button');
            }

            this.navigationElementType = options.navigationElementType;
        },

        render: function () {
            var data, titleSerialized, titleShort;

            this.updateState();

            data = this.getTemplateData();
            if (!data) {
                // no data, it is initial auto render, skip rendering
                return this;
            }

            if (data.navigationElements && data.navigationElements[this.navigationElementType]) {
                titleShort = data.titleShort;
                this.$el.show();
                /**
                 * Setting serialized titles for pinbar button
                 */
                if (data.titleSerialized) {
                    titleSerialized = JSON.parse(data.titleSerialized);
                    this.$el.data('title', titleSerialized);
                }
                this.$el.data('title-rendered-short', titleShort);
            } else {
                this.$el.hide();
            }

            return this;
        },

        updateState: function () {
            var model;
            model = this.collection.getCurrentModel();
            this.$el.toggleClass('gold-icon', model != null);
        },

        onToggle: function () {
            var model, attrs, Model;
            model = this.collection.getCurrentModel();
            if (model) {
                this.collection.trigger('toRemove', model);
            } else {
                Model = this.collection.model;
                attrs = this.getItemAttrs();
                model = new Model(attrs);
                this.collection.trigger('toAdd', model);
            }
        },

        getItemAttrs: function () {
            var attrs, title;
            title = this.$el.data('title');
            attrs = {
                url: mediator.execute('currentUrl'),
                'title_rendered': document.title,
                'title_rendered_short': this.$el.data('title-rendered-short') || document.title,
                title: title ? JSON.stringify(title) : '{"template": "' + document.title + '"}'
            };
            return attrs;
        }
    });

    return BookmarkButtonView;
});
