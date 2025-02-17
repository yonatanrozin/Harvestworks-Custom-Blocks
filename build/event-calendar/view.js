/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./src/event-calendar/view.js ***!
  \************************************/
/* eslint-disable no-console */

const offsets = new Array(6).fill(undefined).map(() => {
  const cell = document.createElement("div");
  cell.classList.add("calendar_offset");
  cell.style.height = "100%";
  return cell;
});
const cells = document.querySelectorAll(".calendar_day");
const url = new URL(window.location.href);

// month & year being viewed on the calendar:
// get from url search params or default to current date
let dateView = dateFromACFField(url.searchParams.get("date"));
if (!dateView) {
  url.searchParams.delete("date");
  history.pushState({}, "", url);
  window.dispatchEvent(new PopStateEvent("popstate"));
  dateView = new Date();
}
const month = dateView.getMonth();
const year = dateView.getFullYear();
const firstOfMonth = new Date(year, month, 1).getDay();
document.querySelector(".calendar_day").style.gridColumnStart = firstOfMonth + 1;
document.querySelector("#calendar_date h3").innerHTML = dateView.toLocaleString("default", {
  month: "short"
}) + " " + dateView.toLocaleString("default", {
  year: "numeric"
});
function dateFromACFField(date) {
  const len = date?.length;
  if (len !== 8) return null;
  const year = date.substring(0, 4);
  const month = date.substring(4, 6) - 1; //JS months counting from 0
  const day = date.substring(6, 8);
  const jsdate = new Date(year, month, day);
  if (jsdate == "Invalid Date") return null;
  return jsdate;
}
function calendarSetup(events = window.hw_events) {
  window.hw_events = events;
  const dateSearch = url.searchParams.get("date") || new Date().toISOString().split('T')[0].split("-").join("");
  const month = dateView.getMonth();
  const year = dateView.getFullYear();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  for (const cell of cells) {
    if (Number(cell.id) > daysInMonth) {
      cell.style.display = "none";
      continue;
    }
    cell.style.display = "flex";

    //cell represented date = viewed year/month on calendar + cell id as day
    const cellDate = new Date(year, month, cell.id).toISOString().split("T")[0].split("-").join("");
    const isQueried = dateSearch === cellDate;
    if (isQueried) {
      cell.classList.add("queried");
    } else cell.classList.remove("queried");
    function setURLDateQuery() {
      const newParam = cellDate;
      const oldParam = url.searchParams.get("date");
      if (newParam == oldParam) {
        url.searchParams.delete("date");
        window.sessionStorage.removeItem("hw_calendar_date");
      } else {
        window.sessionStorage.setItem("hw_calendar_date", newParam);
        url.searchParams.set("date", newParam);
      }
      history.pushState({}, "", url);
      window.dispatchEvent(new PopStateEvent("popstate"));
    }
    for (const i in events) {
      const event = events[i];
      try {
        const {
          date,
          end_date
        } = event.acf;
        const start = date.value;
        const end = end_date?.value;
        const hasEvent = cellDate >= start && cellDate <= (end !== null && end !== void 0 ? end : start);
        cell.onclick = setURLDateQuery;
        if (hasEvent) {
          cell.classList.add("has_event");
          break;
        } else cell.classList.remove("has_event");
      } catch (e) {
        console.error(e.message, event.title.rendered, event);
        continue;
      }
    }
  }
  const firstOfMonth = new Date(year, month, 1).getDay();
  document.querySelector(".calendar_day").style.gridColumnStart = firstOfMonth + 1;
  document.querySelector("#calendar_date h3").innerHTML = dateView.toLocaleString("default", {
    month: "short"
  }) + " " + dateView.toLocaleString("default", {
    year: "numeric"
  });
}
function nextMonth() {
  dateView.setMonth(dateView.getMonth() + 1);
  calendarSetup();
}
function prevMonth() {
  dateView.setMonth(dateView.getMonth() - 1);
  calendarSetup();
}
document.getElementById("nextMonthButton").addEventListener("click", nextMonth);
document.getElementById("prevMonthButton").addEventListener("click", prevMonth);
window.addEventListener("popstate", () => calendarSetup());
fetch("/wp-json/wp/v2/month").then(res => res.json()).then(calendarSetup);

/* eslint-enable no-console */
/******/ })()
;
//# sourceMappingURL=view.js.map