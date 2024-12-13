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
import { Howl, Howler } from 'howler';

const timeKey = 'radio-timestamp';
const urlKey = 'radio-file';
const pauseKey = 'radio-pause';
const listenedKey = 'radio-listened';

Howler.autoUnlock = true;
Howler.html5PoolSize = 100;

// Get the audio urls passed through html
const urls = document.querySelector('.audioFiles').textContent;

// Remove empty lines and extract the audio urls from auto link tagging
const audioFiles = urls.split('\n').filter((url) => url.trim() !== '').map((url) => url.replaceAll("[audio src=\"", "").replaceAll("\" /]", "").trim());
console.log('Audio files:', audioFiles);

// Get the potentially stored time and url
const storedTime = window.localStorage.getItem(timeKey);
const storedUrl = window.localStorage.getItem(urlKey);

startAudio();




function shufflePlaylist() {
    let currentIndex = audioFiles.length, randomIndex;

    while (currentIndex !== 0) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        [audioFiles[currentIndex], audioFiles[randomIndex]] = [audioFiles[randomIndex], audioFiles[currentIndex]];
    }
}

function startAudio() {
    if (audioFiles.length === 0) {
        console.log('No audio files to play on radio.');
        return;
    }

    const listened = window.localStorage.getItem(listenedKey) || '';
    if (listened.split('\n').length === audioFiles.length) {
        console.log('All audio files have been listened to. Restarting.');
        window.localStorage.setItem(listenedKey, '');
        return setupAudio;
    }

    // Filter listened to tracks from audioFiles
    listened.split('\n').forEach((url) => {
        const index = audioFiles.indexOf(url);
        if (index > -1) {
            audioFiles.splice(index, 1);
        }
    });

    var sound;

    // Play the last pages track if it exists
    if (storedUrl && audioFiles.includes(storedUrl) && storedTime) {
        sound = new Howl({
            src: storedUrl,
            html5: true,
            volume: 0.5,
        });

        sound.seek(storedTime);

        if (!window.localStorage.getItem(pauseKey))
            sound.play();

        sound.once("end", () => {
            audioFiles.splice(audioFiles.indexOf(storedUrl), 1);

            window.localStorage.setItem(timeKey, 0);
            window.localStorage.setItem(urlKey, '');
            window.localStorage.setItem(listenedKey, listened + '\n' + storedUrl);

            startAudio();
        });
    }
    // Play a random track if no existing session
    else {
        shufflePlaylist();
        sound = new Howl({
            src: audioFiles,
            html5: true,
            volume: 0.5
        });

        sound.once("end", () => {
            audioFiles.splice(audioFiles.indexOf(sound._src), 1);

            window.localStorage.setItem(timeKey, 0);
            window.localStorage.setItem(urlKey, '');
            window.localStorage.setItem(listenedKey, listened + '\n' + storedUrl);
        });

        // No autoplay unless testing
        sound.play();
    }

    sound.once('play', () => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        window.localStorage.setItem(pauseKey, false);
        console.log('Playing track ' + sound._src);
    });

    sound.once('seek', () => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        console.log('Seeking track ' + sound._src + ' to ' + sound.seek());
    });

    sound.once('pause', () => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        window.localStorage.setItem(pauseKey, true);
        console.log('Pausing track ' + sound._src);
    });

    sound.once('stop', () => {
        window.localStorage.setItem(timeKey, 0);
        window.localStorage.setItem(urlKey, '');
        console.log('Stopping track ' + sound._src);
    });

    sound.once('unlock', () => {
    });
}



