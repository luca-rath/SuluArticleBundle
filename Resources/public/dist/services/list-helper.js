define(["underscore","services/husky/translator","suluarticle/utils/template-helper"],function(a,b,c){"use strict";var d=function(c){for(var d=a.keys(c),e=d.length,f={},g=0;g<e;g++){var h=d[g];f[h]=b.translate(c[h])}return f},e=d({filterAll:"sulu_article.list.filter.all",from:"sulu_article.authored-selection-overlay.from",to:"sulu_article.authored-selection-overlay.to",published:"public.published",unpublished:"public.unpublished",publishedWithDraft:"public.published-with-draft",shadowArticle:"sulu_article.shadow_article",filterByAuthor:"sulu_article.list.filter.by-author",filterMe:"sulu_article.list.filter.me",filterByCategory:"sulu_article.list.filter.by-category",filterByTag:"sulu_article.list.filter.by-tag",filterByPage:"sulu_article.list.filter.by-page",filterByTimescale:"sulu_article.list.filter.by-timescale"}),f={draftIcon:c.transformTemplateData(a.template('<span class="draft-icon" title="<%= data.title %>"/>')),publishedIcon:c.transformTemplateData(a.template('<span class="published-icon" title="<%= data.title %>"/>')),shadowIcon:c.transformTemplateData(a.template('<span class="fa-share" title="<%= data.title %>"></span>'))};return{translations:e,getAuthoredTitle:function(a){if(!a)return e.filterAll;var b=[];return a.from&&(b.push(e.from),b.push(App.date.format(a.from+"T00:00"))),a.to&&(b.push(b.length>0?e.to.toLowerCase():e.to),b.push(App.date.format(a.to+"T00:00"))),0===b.length?e.filterAll:b.join(" ")},getPublishedTitle:function(a){return a?"published"===a?e.published:e.unpublished:e.filterAll},getFilterTitle:function(a){if(!a)return e.filterAll;switch(a.filterKey){case"filterByAuthor":return e.filterByAuthor+" "+a.contact.firstName+" "+a.contact.lastName;case"me":return e.filterMe;case"filterByCategory":return e.filterByCategory+" "+a.category.name;case"filterByTag":return e.filterByTag+" "+a.tag.name;case"filterByPage":return e.filterByPage+" "+a.page.title}return e.filterAll},generateWorkflowBadge:function(a,b){var c="",d=e.unpublished;return a.published&&!a.publishedState&&(d=e.publishedWithDraft,c+=f.publishedIcon({title:d})),a.publishedState||(c+=f.draftIcon({title:d})),b.title=c,b.cssClass="badge-none",b},generateLocalizationBadge:function(a,b,c){return a.localizationState&&"ghost"===a.localizationState.state&&a.localizationState.locale!==c?(b.title=a.localizationState.locale,b):!(!a.localizationState||"shadow"!==a.localizationState.state)&&(b.title=f.shadowIcon({title:e.shadowArticle}),b.cssClass="badge-none badge-color-black",b)}}});