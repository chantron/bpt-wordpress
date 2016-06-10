(function($) {
    'use strict';
    var eventFeed = new Ractive({
        el: bptEventFeed.containerId,
        template: bptEventFeed.templateId,
        data: {
            title: bptEventFeed.title,
            loading: true,
            defaultImage: bptEventFeed.defaultImage,
        }
    });

    $.ajax({
        method: 'GET',
        url: bptEventFeed.url,
        data: {
            nonce: bptEventFeed.nonce,
            action: 'bpt_get_feed_events',
            id: bptEventFeed.feedId
        }
    }).done(function (response, status) {
        response = JSON.parse(response);
        eventFeed.set('events', response.event);
    }).always(function () {
        eventFeed.set('loading', false);
    })

})(jQuery);
