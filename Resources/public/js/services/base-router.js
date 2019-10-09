/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

define(['underscore', 'services/husky/mediator', 'suluarticle/utils/template-helper'], function(_, Mediator, TemplateHelper) {

    'use strict';

    var constants = {
            defaultTab: 'details'
        },

        routes = {
            list: TemplateHelper.transformTemplateData(_.template('articles/<%= data.locale %>')),
            add: TemplateHelper.transformTemplateData(_.template('articles/<%= data.locale %>/add')),
            edit: TemplateHelper.transformTemplateData(_.template('articles/<%= data.locale %>/edit:<%= data.id %>/<%= data.tab %>')),
            editPage: TemplateHelper.transformTemplateData(_.template('articles/<%= data.locale %>/edit:<%= data.id %>/page:<%= data.page %>/<%= data.tab %>')),
            addPage: TemplateHelper.transformTemplateData(_.template('articles/<%= data.locale %>/edit:<%= data.id %>/add-page/<%= data.tab %>'))
        },

        goto = function(route, trigger, force) {
            if (typeof trigger === 'undefined') {
                trigger = true;
            }

            Mediator.emit('sulu.router.navigate', route, trigger, (force || false));
        };

    return {
        toList: function(locale) {
            goto(routes.list({locale: locale}));
        },
        toAdd: function(locale) {
            goto(routes.add({locale: locale}));
        },
        toEdit: function(id, locale, tab) {
            goto(routes.edit({id: id, locale: locale, tab: (tab || constants.defaultTab)}));
        },
        toEditForce: function(id, locale, tab) {
            goto(routes.edit({id: id, locale: locale, tab: (tab || constants.defaultTab)}), true, true);
        },
        toEditUpdate: function(id, locale, tab) {
            goto(routes.edit({id: id, locale: locale, tab: (tab || constants.defaultTab)}), true, true);
        },
        toPageEdit: function(id, page, locale) {
            goto(routes.editPage({id: id, page: page, locale: locale, tab: constants.defaultTab}));
        },
        toPageAdd: function(id, locale) {
            goto(routes.addPage({id: id, locale: locale, tab: constants.defaultTab}));
        }
    };
});
