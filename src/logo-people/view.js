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
var jsmediatags = window.jsmediatags;

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

// Get elements for the radio
const containerEl = document.querySelector('.spacer');
const buttonEl = document.querySelector('.radioButton');
const titleEl = document.querySelector('.title.radioPart');
const albumEl = document.querySelector('.album.radioPart');
const timeEl = document.querySelector('.time.radioPart');

var intervalId = null;

var tryAutoplay = false;
startAudio();

buttonEl.addEventListener('click', () => {
    if (!containerEl.classList.contains('active')) {
        tryAutoplay = true;
        startAudio();
    }
    else {
        Howler.stop();
    }
    containerEl.classList.toggle('active');
});



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

        if (!(window.localStorage.getItem(pauseKey) == 'true')) {
            sound.play();
            containerEl.classList.add('active');
        }

        sound.on("end", () => {
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

        sound.on("end", () => {
            audioFiles.splice(audioFiles.indexOf(sound._src), 1);

            window.localStorage.setItem(timeKey, 0);
            window.localStorage.setItem(urlKey, '');
            window.localStorage.setItem(listenedKey, listened + '\n' + storedUrl);
        });

        if (tryAutoplay)
            sound.play();
    }

    sound.on('play', () => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        window.localStorage.setItem(pauseKey, 'false');
        console.log('Playing track ' + sound._src);

        updateMetadata(sound._src);
        updateDuration(sound);
        startProgressInterval(sound);

    });

    sound.on('seek', () => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        console.log('Seeking track ' + sound._src + ' to ' + sound.seek());
    });

    sound.on('pause', () => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        window.localStorage.setItem(pauseKey, 'true');
        console.log('Pausing track ' + sound._src);
    });

    sound.on('stop', () => {
        window.localStorage.setItem(timeKey, 0);
        window.localStorage.setItem(urlKey, '');
        console.log('Stopping track ' + sound._src);
        stopProgressInterval();
    });

    sound.on('unlock', () => {
    });


}


function updateMetadata(currentURL) {
    jsmediatags.read(currentURL, {
        onSuccess: function (tag) {
            console.log(tag);
            titleEl.textContent = tag.tags.title;
            if (tag.tags.artist)
                titleEl.textContent += '  -  ' + tag.tags.artist;
            albumEl.textContent = tag.tags.album;
            if (tag.tags.year)
                albumEl.textContent += '  (' + tag.tags.year + ')';
        },
        onError: function (error) {
            console.log(':(', error.type, error.info);
        }
    });
}

function updateDuration(sound) {
    const seconds = sound._duration;
    timeEl.textContent = timeEl.textContent.split('/')[0] + '/ ' + Math.floor(seconds / 60) + ':' + Math.floor(seconds % 60);
}

function updateProgress(sound) {
    const seconds = sound.seek();
    timeEl.textContent = Math.floor(seconds / 60) + ':' + Math.floor(seconds % 60) + ' /' + timeEl.textContent.split('/')[1];
}

function startProgressInterval(sound) {
    stopProgressInterval();
    intervalId = setInterval(() => {
        window.localStorage.setItem(timeKey, sound.seek());
        window.localStorage.setItem(urlKey, sound._src);
        updateProgress(sound);
    }, 1000);
}

function stopProgressInterval() {
    clearInterval(intervalId);
}