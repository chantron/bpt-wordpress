{{ #events }}
    <div class="bpt-event-feed-event row">
        <div class="col col-4 bpt-event-feed-image-container">
			<a href="https://www.brownpapertickets.com/event/{{event_id}}" target="_blank">
		{{ #images.0.large }}
            <img class="bpt-event-feed-event-image" src="{{ images.0.large }}">
		{{ /images.0.large }}
		{{ ^images.0.large }}
			<img
				class="bpt-event-feed-event-image bpt-event-feed-event-default-image"
				src="{{ defaultImage }}"
			>
		{{ /images.0.large}}
			</a>
        </div>
        <div class="col col-8 bpt-event-feed-event-details-container">
			<h4 class="bpt-event-feed-event-title"><a href="https://www.brownpapertickets.com/event/{{event_id}}" target="_blank">{{ title }}</a></h4>
            <p class="bpt-event-feed-event-description">
				{{{ description }}}
			</p>
        </div>
    </div>
{{ /events }}
