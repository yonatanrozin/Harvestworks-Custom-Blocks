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

const allEventDetails = Object.fromEntries(event_JSON.map(e => [e.id, e]));
const loopBlock = document.querySelector(".wp-block-post-template");
const allPosts = Array.from(document.querySelectorAll(".wp-block-post"));

//look for element class "post-###", get the number, lookup in event details
function eventDetailsFromElement(e) {
    return allEventDetails[Number(Array.from(e.classList).find(c => /post-\d+/.test(c)).split("-")[1])];
}

function dateFromACFField(date) {
    if (!date) return null;
    const year = date.substring(0, 4);
    const month = date.substring(4, 6) - 1; //JS months counting from 0
    const day = date.substring(6, 8);
    return new Date(year, month, day); 
}

function filterList() {
    //get valid searched date from URL, default to current date, ignore time
    const dateParam = new URL(window.location.href).searchParams.get("date");
    let searchStart = new Date(dateParam === null ? Date.now() : dateParam);
    if (searchStart == "Invalid Date") searchStart = new Date();
    searchStart.setHours(0, 0, 0);

    loopBlock.innerHTML = "";

    const searchEnd = new Date(searchStart);
    searchEnd.setDate(searchStart.getDate() + 1);

    for (const post of allPosts) {
        const {title, date} = eventDetailsFromElement(post);
        let [startDate, endDate] = date.map(d => dateFromACFField(d)?.valueOf());
        if (!endDate) endDate = startDate;

        //if a date searched, get events occurring on that day ONLY, else get all upcoming events
        const match = dateParam?
            endDate >= searchStart.valueOf() && startDate < searchEnd.valueOf() :
            endDate >= searchStart.valueOf();

        if (match) loopBlock.prepend(post);
    }
}

filterList();

window.addEventListener("popstate", filterList);


/* eslint-enable no-console */
