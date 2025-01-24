/******/ (() => { // webpackBootstrap
/*!***************************************!*\
  !*** ./src/event-information/view.js ***!
  \***************************************/
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

const backButton = document.querySelector('.event-back-button');
if (backButton) {
  backButton.addEventListener('click', () => {
    const loc = window.location.href.split('?');
    const params = '';
    if (loc.length > 1) params = loc[1];
    window.location.href = '/events?' + params;
  });
}
const postContent = document.querySelector('.entry-content');
const pressQuotes = document.querySelector('.press-quotes');

// if they both exist, move press quotes to after the content
if (postContent && pressQuotes) {
  postContent.insertAdjacentElement('afterend', pressQuotes);
}
/******/ })()
;
//# sourceMappingURL=view.js.map