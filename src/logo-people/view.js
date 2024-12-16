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


import { Radio } from './radio';

const radio = new Radio(window, document, true);

// Get the audio urls passed through html

// Remove empty lines and extract the audio urls from auto link tagging
// console.log('Audio files:', audioFiles);


// Get the potentially stored time and url
// const storedTime = window.localStorage.getItem(timeKey);
// const storedUrl = window.localStorage.getItem(urlKey);

// Get elements for the radio


// var intervalId = null;
// var sound;
// var tryAutoplay = false;
// loadAudio();

// buttonEl.addEventListener('click', () => {
//     if (!containerEl.classList.contains('active')) {
//         tryAutoplay = true;
//         if (!sound)
//             loadAudio();
//         else if (!(window.localStorage.getItem(pauseKey) == 'true'))
//             sound.play();
//     }
//     else {
//         sound.pause();
//     }
//     containerEl.classList.toggle('active');
// });

// pauseEl.addEventListener('click', () => {
//     sound.pause();
//     containerEl.classList.add('pause');
// });

// playEl.addEventListener('click', () => {
//     sound.play();
//     containerEl.classList.remove('pause');
// });

// skipEl.addEventListener('click', () => {
//     sound.seek(sound._duration);
// });

// prevEl.addEventListener('click', () => {
//     if (sound.seek() > 5000)
//         sound.seek(0);
//     else {
//         const listened = window.localStorage.getItem(listenedKey) || '';
//         window.localStorage.setItem(listenedKey, listened.split('\n').slice(0, -1).join('\n'));
//         sound.play(listened.split('\n').slice(-1)[0]);
//     }
// });



// function shufflePlaylist() {
//     let currentIndex = audioFiles.length, randomIndex;

//     while (currentIndex !== 0) {
//         randomIndex = Math.floor(Math.random() * currentIndex);
//         currentIndex--;

//         [audioFiles[currentIndex], audioFiles[randomIndex]] = [audioFiles[randomIndex], audioFiles[currentIndex]];
//     }
// }

// function loadAudio() {
// var listened = window.localStorage.getItem(listenedKey) || '';

// // Filter listened to tracks from audioFiles
// listened.split('\n').forEach((url) => {
//     const index = audioFiles.indexOf(url);
//     if (index > -1) {
//         audioFiles.splice(index, 1);
//     }
// });

// if (audioFiles.length === 0) {
//     console.log('All audio files have been listened to. Restarting.');
//     audioFiles = originalAudioFiles.flat();
//     window.localStorage.setItem(listenedKey, '');
//     return loadAudio();
// }

// Play the last pages track if it exists
// if (storedUrl && audioFiles.includes(storedUrl) && storedTime) {
//     sound = new Howl({
//         src: storedUrl,
//         html5: true,
//         volume: 0.5,
//     });

//     sound.seek(storedTime);

//     if (!(window.localStorage.getItem(pauseKey) == 'true')) {
//         sound.play();
//         containerEl.classList.add('active');
//     }

//     sound.once("end", () => {
//         audioFiles.splice(audioFiles.indexOf(storedUrl), 1);

//         window.localStorage.setItem(timeKey, 0);
//         window.localStorage.setItem(urlKey, '');
//         window.localStorage.setItem(listenedKey, listened + '\n' + storedUrl);

//         loadAudio();
//     });
// }
// Play a random track if no existing session
// else {
//     shufflePlaylist();
//     sound = new Howl({
//         src: audioFiles,
//         html5: true,
//         volume: 0.5
//     });

// if (tryAutoplay)
//     sound.play();

//     sound.once("end", () => {
//         audioFiles.splice(audioFiles.indexOf(sound._src), 1);

//         listened = window.localStorage.getItem(listenedKey) || '';

//         window.localStorage.setItem(timeKey, 0);
//         window.localStorage.setItem(urlKey, '');
//         window.localStorage.setItem(listenedKey, listened + '\n' + storedUrl);

//         tryAutoplay = true;
//         loadAudio();
//     });
// }

//     sound.on('play', () => {
//         window.localStorage.setItem(timeKey, sound.seek());
//         window.localStorage.setItem(urlKey, sound._src);
//         window.localStorage.setItem(pauseKey, 'false');
//         console.log('Playing track ' + sound._src);

//         updateMetadata(sound._src);
//         updateDuration(sound);
//         startProgressInterval(sound);

//     });

//     sound.on('seek', () => {
//         window.localStorage.setItem(timeKey, sound.seek());
//         window.localStorage.setItem(urlKey, sound._src);
//         console.log('Seeking track ' + sound._src + ' to ' + sound.seek());

//         if (sound.seek() > sound._duration - 1) {
//             audioFiles.splice(audioFiles.indexOf(sound._src), 1);

//             listened = window.localStorage.getItem(listenedKey) || '';

//             window.localStorage.setItem(timeKey, 0);
//             window.localStorage.setItem(urlKey, '');
//             window.localStorage.setItem(listenedKey, listened + '\n' + sound._src);

//             loadAudio();
//         }
//     });

//     sound.on('pause', () => {
//         window.localStorage.setItem(timeKey, sound.seek());
//         window.localStorage.setItem(urlKey, sound._src);
//         window.localStorage.setItem(pauseKey, 'true');
//         console.log('Pausing track ' + sound._src);
//     });

//     sound.on('stop', () => {
//         window.localStorage.setItem(timeKey, 0);
//         window.localStorage.setItem(urlKey, '');
//         console.log('Stopping track ' + sound._src);
//         stopProgressInterval();
//     });

//     sound.on('unlock', () => {
//     });
// }


// function updateMetadata(currentURL) {
//     jsmediatags.read(currentURL, {
//         onSuccess: function (tag) {
//             console.log(tag);
//             titleEl.textContent = tag.tags.title;
//             if (tag.tags.artist)
//                 titleEl.textContent += ' — ' + tag.tags.artist;
//             albumEl.textContent = tag.tags.album;
//             if (tag.tags.year)
//                 albumEl.textContent += ' (' + tag.tags.year + ')';
//         },
//         onError: function (error) {
//             console.log(':(', error.type, error.info);
//         }
//     });
// }

// function updateDuration(sound) {
//     const seconds = sound._duration;
//     if (!sound) {
//         timeEl.textContent = timeEl.textContent.split('/')[0] + '/ -:--';
//         return;
//     }
//     var dispSec = Math.floor(seconds % 60);
//     if (dispSec < 10)
//         dispSec = '0' + dispSec;

//     timeEl.textContent = timeEl.textContent.split('/')[0] + '/ ' + Math.floor(seconds / 60) + ':' + dispSec;
// }

// function updateProgress(sound) {
//     const seconds = sound.seek();
//     if (!sound) {
//         timeEl.textContent = '-:--' + ' /' + timeEl.textContent.split('/')[1];
//         return;
//     }

//     var dispSec = Math.floor(seconds % 60);
//     if (dispSec < 10)
//         dispSec = '0' + dispSec;

//     timeEl.textContent = Math.floor(seconds / 60) + ':' + dispSec + ' /' + timeEl.textContent.split('/')[1];
// }

// function startProgressInterval(sound) {
//     stopProgressInterval();
//     updateProgress(sound);
//     intervalId = setInterval(() => {
//         window.localStorage.setItem(timeKey, sound.seek());
//         window.localStorage.setItem(urlKey, sound._src);
//         updateProgress(sound);
//     }, 1000);
// }

// function stopProgressInterval() {
//     clearInterval(intervalId);
// }