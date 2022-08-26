const { Module } = Shopware;
import deDE from './snippet/de-DE';
import enGB from './snippet/en-GB';
import './page/ddms-question-list';
import './page/ddms-answer-list';

Module.register('ddms-question', {
    type: 'plugin',
    name: 'Question Answer',
    title: 'qa.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',
    color: '#A092F0',
    icon: 'small-questionmark',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'ddms-question-list',
            path: 'list',
            meta: {
                parentPath: 'sw.settings.index'
            }
        },
        detail: {
            component: 'ddms-question-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'ddms.question.list'
            }
        }
    },

    settingsItem: {
        name: 'question-answer',
        to: 'ddms.question.list',
        label: 'qa.general.mainMenuItemGeneral',
        group: 'plugins',
        icon: 'small-questionmark'
    } 
});