# Harvestworks Custom Blocks

A collection of blocks that display dynamic content for the Harvestworks website.

## Installation
- Download this repo as a ```.zip``` file.
- Upload file as new plugin in "Plugins" screen in WP dashboard.
- Activate new plugin.

## Wordpress Plugin Dependencies
- [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/)
    - Download manually from ACF website, NOT Wordpress plugins page!
- [GenerateBlocks](https://wordpress.org/plugins/generateblocks/)
    - Install from Wordpress plugins page

## Blocks

### Featured Events Carousel
An auto-scrolling carousel populated with images and information about selected upcoming events.

### Event Calendar
An interactive calendar that displays events. Applies filters to the current URL for use with the Event List block.
- Renders a standard calendar grid of the current month, with buttons to increment the viewed month.
- Calendar days with scheduled events are highlighted a certain color.

#### Usage
- Add block to page/template. A list of upcoming event titles should appear.
- In "Block" tab of right sidebar, choose a color to be shown on days with events and a color to be shown on the current selected date.

#### Creating Events
- Create a new Event post, enter event title in "Add title" section.
- Specify event date, end date (optional) and start/end times (optional) for event in "Event" tab of right sidebar.
- (optional) Select associated projects for event.

### Event List
A customizable list of event details that responds to filters set by the Event Calendar block.
- Renders a list of events using a customizable template. Acts identically to a "query loop" block but responds to URL search parameters by filtering event data. 
- Shows all upcoming events (starting on/after current day) by default. Reacts to "date" URL search param to choose a specific day. To use URL search params, "Event Calendar" block must be present on the same page.

#### Usage
- Add block to page/template.
- Choose a preset loop template or (recommended) start a blank one.
- Customize post template appearance.
    - (optional) Inside template, use "Meta Field" block to display a metadata field (start/end time, etc.)
- Select internal "Query Loop" block. In "Block" tab of right sidebar - "Settings" panel: 
    - Disable "Inherit query from template".
    - Select post type "Event". Ignore "order by".
- Designed for use alongside "Event Calendar" block on the same page.

#### Creating Events
- See "Creating Events" section of Event Calendar block.
- Add a banner image to be displayed in the list using the "Add featured image" button in "Event" tab of right sidebar.
