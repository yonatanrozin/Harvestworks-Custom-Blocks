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

// Add toggle functionality to the navigation overlay button
const navButton = document.querySelector('.nav-overlay-button');
const navOverlay = document.querySelector('.wp-block-navigation__responsive-container.hidden-by-default');
const overlayCloseButton = document.querySelector('.wp-block-navigation__responsive-container-close');
navButton.addEventListener('click', () => {
  navOverlay.classList.toggle('is-menu-open');
  navOverlay.classList.toggle('has-modal-open');
  navButton.classList.toggle('is-menu-open');
});
overlayCloseButton.addEventListener('click', () => {
  navOverlay.classList.toggle('is-menu-open');
  navOverlay.classList.toggle('has-modal-open');
  navButton.classList.toggle('is-menu-open');
});

// Add placeholder text to the search input
const searchInput = document.querySelector('.wp-block-search__input');
searchInput.attributes.placeholder.value = 'Search...';

// Add toggle functionality to submenu ULs
const subMenus = document.querySelectorAll('.wp-block-navigation__responsive-container .wp-block-navigation-item.has-child');
subMenus.forEach(subMenu => {
  const subMenuLink = subMenu.querySelector('.wp-block-navigation-item__content');
  const wrapper = document.createElement('div');
  wrapper.classList.add('submenu-wrapper');
  subMenuLink.parentNode.insertBefore(wrapper, subMenuLink);
  wrapper.appendChild(subMenuLink);
  subMenuLink.insertAdjacentHTML('afterend', '<span class="submenu-toggle">+</span>');
  const submenuToggle = subMenu.querySelector('.submenu-toggle');
  submenuToggle.addEventListener('click', e => {
    e.preventDefault();
    subMenu.classList.toggle('is-open');
    submenuToggle.textContent = subMenu.classList.contains('is-open') ? '—' : '+';
  });
});
const icons = {
  'instagram': '/wp-content/uploads/2025/01/instagram.png',
  'facebook': '/wp-content/uploads/2025/01/facebook.png',
  'twitter': '/wp-content/uploads/2025/01/twitter.png',
  'news': '/wp-content/uploads/2025/01/news.png',
  'youtube': '/wp-content/uploads/2025/01/youtube.png',
  'flickr': '/wp-content/uploads/2025/01/flickr.png',
  'newsletter': '/wp-content/uploads/2025/01/mail.png',
  'tellus': '/wp-content/uploads/2025/01/tellus.png'
};

// Switch icons to preferred images
const iconEls = document.querySelectorAll('.wp-social-link');
iconEls.forEach(iconEl => {
  const anchor = iconEl.querySelector('.wp-block-social-link-anchor');
  const href = anchor.href;
  anchor.innerHTML = `<img src="" alt="" />`;
  const keys = Object.keys(icons);
  keys.forEach(key => {
    if (href.includes(key)) {
      anchor.querySelector('img').src = icons[key];
      anchor.querySelector('img').alt = key;
    }
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map