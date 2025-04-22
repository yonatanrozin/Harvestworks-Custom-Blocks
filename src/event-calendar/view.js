/* eslint-disable no-console */

const debug = true;


// Utils
function dateToStr(date) {
    return date.toISOString().split("T")[0].split("-").join("");
}

function strToDate(str) {
    const year = str.substring(0, 4);
    const month = str.substring(4, 6);
    const day = str.substring(6, 8);
    return new Date(year, month - 1, day);
}

function offsetOfMonth(dateStr) {
    const year = dateStr.substring(0, 4);
    const month = dateStr.substring(4, 6) - 1;
    return new Date(year, month, 1).getDay() + 1;
}

function daysInMonth(dateStr) {
    const year = dateStr.substring(0, 4);
    const month = dateStr.substring(4, 6) - 1;
    return new Date(year, month + 1, 0).getDate();
}

// Session storage
function getURLDateStr() {
    const url = new URL(window.location.href);
    const param = url.searchParams.get("date");
    if (!param || param.length != 8) return dateToStr(new Date());

    try { // Check if the date is valid integer
        parseInt(param);
    } catch (e) {
        return dateToStr(new Date());
    }

    return param;
}

function setURLDateStr(dateStr) {
    if (debug) console.log("Saving date to URL & storage:", dateStr);
    const url = new URL(window.location.href);
    url.searchParams.set("date", dateStr);
    history.pushState({}, "", url);
    window.sessionStorage.setItem("hw_calendar_date", dateStr);
    window.dispatchEvent(new PopStateEvent("popstate"));
}

// Document elements
const dayCells = document.querySelectorAll(".calendar_day");

function initializeCalendar() {
    const dateStr = getURLDateStr();
    if (debug) console.log("Initializing calendar on date:", dateStr);
    setMonth(dateStr);
    setDay(dateStr);
}

// Switch the calendar to a certain month in a certain year
function setMonth(dayStr) {
    const date = strToDate(dayStr);

    // Offset the first cell based on month
    document.querySelector(".calendar_day").style.gridColumnStart = offsetOfMonth(dayStr);
    document.querySelector("#calendar_date h3").innerHTML = date.toLocaleString("default", { month: "short" }) + " " + date.toLocaleString("default", { year: "numeric" });

    for (const dayCell of dayCells) {
        const cellDay = Number(dayCell.id);
        const cellDayStr = dayCell.id.padStart(2, "0");
        const cellDateStr = dayStr.substring(0, 6) + cellDayStr;

        // Hide cells that are beyond the month
        if (cellDay > daysInMonth(dayStr)) {
            dayCell.style.display = "none";
            continue;
        }

        // Reset cell indicators
        dayCell.style.display = "flex";
        dayCell.classList.remove("queried");
        dayCell.classList.remove("has_event");

        // Make cells interactive as their new day
        dayCell.onclick = () => setDay(cellDateStr);
    }

    // Fetch events for the month
    fetch("/wp-json/wp/v2/month?date=" + dayStr)
        .then(res => res.json())
        .then(events => renderEventDays(dayStr, events));

    // Update the month navigation buttons
    document.getElementById("nextMonthButton").onclick = () => nextMonth(dayStr);
    document.getElementById("prevMonthButton").onclick = () => prevMonth(dayStr);
}

// Highlight the days that have events, once the events are fetched
function renderEventDays(dayStr, events) {
    if (!Array.isArray(events)) return console.log('Invalid events data:', events);
    if (events.length === 0) return console.log('No events for the month.');
    if (debug) console.log('Rendering events for month:', dayStr, events);

    for (const dayCell of dayCells) {
        const cellDayStr = dayCell.id.padStart(2, "0");
        const cellDateStr = dayStr.substring(0, 6) + cellDayStr;

        const ongoingEvents = events.filter(event => {
            const eventDay = event.acf.date;
            let eventEndDay = event.acf.end_date;
            if (!eventDay) return false;
            if (!eventEndDay || eventEndDay.length === 0) eventEndDay = eventDay;
            try {
                const match = parseInt(cellDateStr) >= parseInt(eventDay) && parseInt(cellDateStr) <= parseInt(eventEndDay);
                // if (debug && match) console.log('Event match for ' + cellDateStr + ' : ' + eventDay + ' -> ' +eventEndDay + ' (' + event.post_title + ')');
                return match;
            } catch (e) {
                return false;
            }
        });

        // if (debug) console.log(cellDateStr + ' events:', ongoingEvents);

        if (ongoingEvents.length > 0) {
            dayCell.classList.add("has_event");
        } else {
            dayCell.classList.remove("has_event");
        }
    }
}

// Switch the calendar to a certain day
function setDay(dayStr) {
    if (debug) console.log("Setting calendar to day:", dayStr);
    // Update the URL and session storage
    setURLDateStr(dayStr);

    // Highlight the selected day
    for (const dayCell of dayCells) {
        const cellDayStr = dayCell.id.padStart(2, "0");
        const cellDateStr = dayStr.substring(0, 6) + cellDayStr;

        if (cellDateStr === dayStr) {
            dayCell.classList.add("queried");
        } else {
            dayCell.classList.remove("queried");
        }
    }
}

// Navigate to the next or previous month
function nextMonth(dayStr) {
    if (debug) console.log("Navigating to next month from:", dayStr);
    const date = strToDate(dayStr);
    const newDate = new Date(date.getFullYear(), date.getMonth() + 1, 1);
    const newDateStr = dateToStr(newDate);

    setMonth(newDateStr);
    setDay(newDateStr);
}

function prevMonth(dayStr) {
    if (debug) console.log("Navigating to previous month from:", dayStr);
    const date = strToDate(dayStr);
    const newDate = new Date(date.getFullYear(), date.getMonth() - 1, 1);
    const newDateStr = dateToStr(newDate);

    setMonth(newDateStr);
    setDay(newDateStr);
}


initializeCalendar();


