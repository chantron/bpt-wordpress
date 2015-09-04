<div class="form-table">
	<div id="event-select"></div>
	<br><br>
	<div id="date-select"></div>
	<br><br>
	<div id="attendees"></div>
</div>
<!-- The event selector -->
<script id="event-select-template" type="text/html">
	{{#if events}}
		<h2>Select Event</h2>
		<select id="selected-event" name="selected" on-change="select-event" value="{{selected}}">
		{{#each events}}
			<option value="{{.}}">{{title}}</option>
		{{/each}}
		</select>
		<button id="refresh-events" class="button-secondary button-sm" on-click="refresh-events">Refresh Events</button>
	{{/if}}

	{{#unless events}}
	<button class="button-primary" id="refresh-events">Load Events</button>
	{{/unless}}

	{{#if error}}
		<p>{{error}}</p>
	{{/if}}
</script>

<!-- The date selector -->

<script id="date-select-template" type="text/html">
{{#if dates.length}}
<h2>Select Dates</h2>
	{{#dates}}
		<label
			for="date-{{id}}"
			class="date-select"
		>
			{{formatDate(dateStart)}} - {{ formatTime(timeStart) }}
			<input
				id="date-{{id}}"
				type="checkbox"
				name="{{selected}}"
				value="{{id}}"
			>
		</label>
	{{/dates}}
	{{#if selected}}

	{{/if}}
{{/if}}
</script>

<!-- The attendee list -->

<script id="attendee-list-template" type="text/html">
{{#if dates.length}}
<h2>Attendees</h2>
{{#dates:i}}
	<h3>{{formatDate(dateStart)}} - {{ formatTime(timeStart) }}</h3>
	{{#attendees}}
		{{#if inDate(this, dates[i]) }}

				<h4>{{firstName}} {{lastName}}</h4>
				<table>
					<thead>

							<th>Price Name</th>
							<th>Ticket Number</th>
							<th>Section</th>
							<th>Row</th>
							<th>Seat</th>

					</thead>
					<tbody>
						<tr>
							<td>{{priceID}}</td>
							<td>{{ticketNumber}}</td>
							<td>{{section}}</td>
							<td>{{row || "N/A"}}</td>
							<td>{{seat}}</td>
						</tr>
					</tbody>
				</table>

		{{/if}}
	{{/attendees}}
{{/dates}}
{{/if}}
</script>
