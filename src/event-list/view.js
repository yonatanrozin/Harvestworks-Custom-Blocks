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

function dateFromACFField(date) {
    if (!date) return null;
    const year = date.substring(0, 4);
    const month = date.substring(4, 6) - 1; //JS months counting from 0
    const day = date.substring(6, 8);
    return new Date(year, month, day).toLocaleString('default', { "month": "short", "day": "2-digit" });
}

function eventCard(event) {

    try {


        const { post_title, acf, featured_image, guid, status, excerpt } = event;
        const { date, time, end_date, end_time, location, event_type, artists } = acf;

        return `
            <div class="event_card">
                <a class="event_anchor" href="${guid}"></a>
                <div class="event_img" style="background-image: url(${featured_image})" >
                    ${status ? `<span class="event_status">${status}</span>` : ""}
                </div>
                <div class="event_info">
                    <div class="event_details" >
                        <span class="event_dates">
                            <span>${dateFromACFField(date)}${time ? `,` : ""}</span>
                            ${time ? `<span>${time}</span>` : ""}
                            ${end_date ? `<span>-&nbsp;</span><span>${dateFromACFField(end_date)}${end_time ? `,` : ""}</span>` : ""}
                            ${end_time ? `<span>${end_time}</span>` : ""}
                        </span>
                        <span class='divider'>•</span>
                        <span class="event_type">${event_type[0].name}</span>
                        <span class='divider'>•</span>
                        <span class="event_location">${location}</span>
                    </div>
                    <div class="event_name">
                        <h2>
                            <span class="event_title">${post_title}</span>
                            ${artists ? `<span class="event_artists">by ${artists}</span>` : ""}
                        </h2>
                    </div>
                    <p class="event_description">${excerpt}</p>
                </div>
            </div>
        `;
    }
    catch (e) {
        console.error(e.message, event);
        return "";
    }
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
