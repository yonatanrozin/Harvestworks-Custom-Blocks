/******/ (() => { // webpackBootstrap
/*!********************************!*\
  !*** ./src/event-list/view.js ***!
  \********************************/
/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

/* eslint-disable no-console */

const block_div = document.querySelector(".wp-block-harvestworks-event-list");
function eventCard(event) {
  const {
    post_title,
    acf,
    featured_image,
    post_excerpt
  } = event;
  const {
    date,
    time,
    location,
    event_type,
    artists
  } = acf;
  return `
        <div class="event_card">
            ${featured_image ? `<img class="event_img" src=${featured_image}></img>` : ""}
            <div class="event_info">
                <div class="event_details" >
                    <span class="event_date">${date.split(",")[0]}</span>
                    <span>•</span>
                    <span class="event_type">${event_type?.name}</span>
                    <span>•</span>
                    <span class="event_location">${location}</span>
                </div>
                <h2 class="event_name">
                    <span class="event_title">${post_title}</span>
                    <span class="event_artist">by ${artists}</span>
                </h2>
                <p class="event_description">${post_excerpt}</p>
            </div>
        </div>
    `;
}
async function getEvents() {
  let dateparam = new URL(window.location.href).searchParams.get("date");
  const queryURL = `/wp-json/wp/v2/events${dateparam ? `?date=${dateparam}` : ""}`;
  const events = await (await fetch(queryURL)).json();
  block_div.innerHTML = events.map(e => eventCard(e)).join("");
}
document.addEventListener("DOMContentLoaded", getEvents);
window.addEventListener("popstate", getEvents);

/* eslint-enable no-console */
/******/ })()
;
//# sourceMappingURL=view.js.map