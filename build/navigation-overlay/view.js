/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./src/navigation-overlay/view.js ***!
  \****************************************/
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

const navButton = document.querySelector('.nav-overlay-button');
const navOverlay = document.querySelector('.wp-block-navigation__responsive-container.hidden-by-default');
const overlayCloseButton = document.querySelector('.wp-block-navigation__responsive-container-close');
navButton.addEventListener('click', () => {
  navOverlay.classList.toggle('is-menu-open');
  navOverlay.classList.toggle('has-modal-open');
});
overlayCloseButton.addEventListener('click', () => {
  navOverlay.classList.toggle('is-menu-open');
  navOverlay.classList.toggle('has-modal-open');
});
const searchInput = document.querySelector('.wp-block-search__input');
searchInput.attributes.placeholder.value = 'Search...';
/******/ })()
;
//# sourceMappingURL=view.js.map