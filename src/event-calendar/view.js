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

const offsets = new Array(6).fill(undefined).map(() => {
    const cell = document.createElement("div");
    cell.classList.add("calendar_offset");
    cell.style.height = "100%";
    return cell;
})
const cells = document.querySelectorAll(".calendar_day");
const url = new URL(window.location.href);

// month/year being viewed on the calendar:
// get from url search params or default to current date
let dateView = dateFromACFField(url.searchParams.get("date")) || new Date(); 
if (dateView == "Invalid Date") dateView = new Date();

function dateFromACFField(date) {
    if (!date) return null;
    const year = date.substring(0, 4);
    const month = date.substring(4, 6) - 1; //JS months counting from 0
    const day = date.substring(6, 8);
    return new Date(year, month, day); 
}

function calendarSetup() {

    const dateSearch = url.searchParams.get("date"); //queried date from URL searchparams

    console.log(dateView);

    const month = dateView.getMonth();
    const year = dateView.getFullYear();
    
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    for (const cell of cells) {
        if (Number(cell.id) > daysInMonth ) {
            cell.style.display = "none";
            continue;
        }
        cell.style.display = "flex";

        const cellDate = new Date(year, month, cell.id);
        const cellDateFormat = cellDate.toISOString().split("T")[0].split("-").join("");

        const isQueried = dateSearch === cellDateFormat;

        if (isQueried) {
            cell.classList.add("queried");
        } else cell.classList.remove("queried");

        function setURLDateQuery() {
            const newParam = cellDate.toISOString().split("T")[0].split("-").join("");
            const oldParam = url.searchParams.get("date");
            if (newParam == oldParam) {
                url.searchParams.delete("date");
                window.sessionStorage.removeItem("hw_calendar_date");
            }
            else {
                window.sessionStorage.setItem("hw_calendar_date", newParam);
                url.searchParams.set("date", newParam);
            }
            history.pushState({}, "", url);
            window.dispatchEvent(new PopStateEvent("popstate"));
        }

        for (const event of window.events) {
            const eventStart = dateFromACFField(event.start_date).valueOf();
            const eventEnd = event.end_date ? dateFromACFField(event.end_date).valueOf() : eventStart;
            const hasEvent = cellDate.valueOf() >= eventStart && cellDate.valueOf() <= eventEnd;
            if (hasEvent) {
                cell.classList.add("has_event");
                cell.onclick = setURLDateQuery;
                break;
            } else cell.classList.remove("has_event");
        }
    }

    const firstOfMonth = new Date(year, month, 1).getDay();

    document.querySelector(".calendar_day").style.gridColumnStart = firstOfMonth + 1;
    document.querySelector("#calendar_date h3").innerHTML = 
        dateView.toLocaleString("default", {month: "short"}) + " " +
        dateView.toLocaleString("default", {year: "numeric"});
}

function nextMonth() {
    dateView.setMonth(dateView.getMonth() + 1);
    calendarSetup();
}
function prevMonth() {
    dateView.setMonth(dateView.getMonth() - 1);
    calendarSetup();
}

document.getElementById("nextMonthButton").addEventListener("click", nextMonth)
document.getElementById("prevMonthButton").addEventListener("click", prevMonth)

window.addEventListener("popstate", calendarSetup);
calendarSetup();

/* eslint-enable no-console */
