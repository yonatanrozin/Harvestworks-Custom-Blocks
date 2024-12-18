import { Howl, Howler } from 'howler';

export class Radio {

    // Settings
    VOLUME = 0.5;
    ERROR_MESSAGE_TIMEOUT = 5000;
    PROGRESS_UPDATE_INTERVAL = 1000;
    RESTART_VS_PREV_TIMEOUT = 2500;

    // Classname constants
    IS_RADIO_ACTIVE_CLASSNAME = 'active';
    IS_RADIO_PAUSED_CLASSNAME = 'pause';

    // Local storage constants
    RECOVERY_TIMESTAMP_KEY = 'radio-timestamp';
    RECOVERY_SONG_URL_KEY = 'radio-file';
    RECOVERY_PAUSE_STATE_KEY = 'radio-pause';
    SONG_HISTORY_KEY = 'radio-listened';

    trackList = [];
    unplayedSongs = [];

    /**
     * @param {Window} window - The global window object.
     * @param {Document} document - The global document object.
     * @param {boolean} debug - if console should show log
     */
    constructor(window, document, debug) {
        this.window = window;
        this.document = document;
        this.debug = debug;

        this.interfaceUpdateIntervalId = null;

        this.sound = null;

        // Document hooks
        this.elements = {
            urlEl: document.querySelector('.audioFiles'),
            containerEl: document.querySelector('.spacer'),
            titleEl: document.querySelector('.title.radioPart'),
            titleWrapperEl: document.querySelector('.titleWrapper'),
            timeEl: document.querySelector('.time.radioPart'),

            // Control hooks
            buttonEl: document.querySelector('.radioButton'),
            pauseEl: document.querySelector('.pause.radioPart'),
            playEl: document.querySelector('.play.radioPart'),
            skipEl: document.querySelector('.skip.radioPart'),
            prevEl: document.querySelector('.prev.radioPart')
        };

        this.displayUserMessage('Loading...', 0);

        // Check for missing elements
        for (const [key, value] of Object.entries(this.elements)) {
            if (!value) {
                this.displayErrorMessage(`Couldn't find radio element: ${key}`);
            }
        }

        // Howler.autoUnlock = true;
        // Howler.html5PoolSize = 100;
        if (!Howler || !Howl) {
            this.displayErrorMessage('Couldn\'t activate player library.');
        }

        this.mediaTags = window.jsmediatags;
        if (!this.mediaTags) {
            this.displayErrorMessage('Couldn\'t activate metadata library.');
        }

        this.parseAudioUrls();

        // Load recovered song if unpaused
        if (this.hasRecoveredSong()) {
            if (!this.recoveredPauseState)
                this.resumeRecoveredSong();
        }

        // Add interaction event listeners
        this.elements.buttonEl.addEventListener('click', () => {
            if (this.isRadioOpen) {
                this.isRadioOpen = false;
                this.pauseSong();
            }
            else {
                this.isRadioOpen = true;
                this.resumeSong();
            }
        });

        this.elements.pauseEl.addEventListener('click', () => {
            this.pauseSong();
        });

        this.elements.playEl.addEventListener('click', () => {
            this.resumeSong();
        });

        this.elements.skipEl.addEventListener('click', () => {
            this.nextSong();
        });

        this.elements.prevEl.addEventListener('click', () => {
            this.prevSong();
        });

        this.window.addEventListener('resize', () => {
            this.updateTitleScroll();
        });
    }

    // Utilities

    log(message) {
        if (this.debug)
            console.log('[RADIO] ' + message);
    }

    displayErrorMessage(message) {
        this.log(message);


        const previousContent = this.elements.titleEl.innerHTML;
        if (previousContent.trim() != '' || previousSubtitle.trim() != '') {
            setTimeout(() => {
                this.elements.titleEl.innerHTML = previousContent;
            }, this.ERROR_MESSAGE_TIMEOUT)
        }

        this.elements.titleEl.textContent = message;
    }

    displayUserMessage(message, timeout) {

        if (timeout != 0) {
            const previousContent = this.elements.titleEl.innerHTML;
            if (previousContent.trim() != '') {
                setTimeout(() => {
                    this.elements.titleEl.innerHTML = previousContent;
                }, timeout);
            }
        }

        this.elements.titleEl.textContent = message;
    }

    // Setup

    parseAudioUrls() {
        const urls = this.elements.urlEl.textContent;
        this.trackList = urls.split('\n')
            .filter((url) => // Filter whitespace
                url.trim() !== ''
            ).map((url) => // Remove mac-os-link markup
                url.replaceAll("[audio src=\"", "")
                    .replaceAll("\" /]", "")
                    .trim()
            );
        this.unplayedSongs = this.trackList.flat();

        if (!this.trackList.length > 0) {
            this.displayErrorMessage('No tracks are available to play.');
        }
    }

    // Controls

    hasRecoveredSong() {
        return this.recoveredSong && this.recoveredTimestamp && this.recoveredSong.trim() != '' && this.recoveredTimestamp.trim() != '';
    }

    resumeRecoveredSong() {
        if (!this.hasRecoveredSong())
            return;

        this.log('Found existing song ' + this.recoveredSong + (this.recoveredPauseState ? ' paused' : ' playing') + ' at ' + this.recoveredTimestamp);
        this.isRadioOpen = true;
        if (!this.trackList.includes(this.recoveredSong))
            return this.log('Recovered song not found in track list.');
        this.playSong(this.recoveredSong, this.recoveredTimestamp, true)
    }

    playSong(url, time = 0, autoplay = false) {
        if (this.sound)
            this.sound.stop();

        this.sound = new Howl({
            src: url,
            html5: true,
            volume: this.VOLUME,
            autoplay: autoplay
        });

        if (time != 0)
            this.sound.seek(time);

        if (autoplay)
            this.isDisplayingPaused = false;
        // this.displayUserMessage('Loading...', 0);

        this.recoveredSong = url;
        this.recoveredTimestamp = time;
        this.recoveredPauseState = autoplay;

        this.sound.on('unlock', () => {
            this.log('Autoplay unlocked.');
            // if (autoplay)
            //     this.sound.play();
        });

        this.sound.once('load', () => {
            this.log('Song loaded ' + this.sound._src);
            this.updateMetadataDisplay(this.sound._src);
        });

        this.sound.on('loaderror', () => {
            this.displayErrorMessage('Error loading track. Skipping...');
            this.log('Error loading track ' + this.sound._src);

            this.isDisplayingPaused = true;
            this.playNextSong();
        });

        this.sound.on('mute', () => {
            this.log('Audio muted.');
        });

        this.sound.on('pause', () => {
            this.log('Song paused ' + this.sound._src + ' at ' + this.sound.seek());
            this.isDisplayingPaused = true;
            this.recoveredPauseState = true;

            this.recoveredSong = this.sound._src;
            this.recoveredTimestamp = this.sound.seek();
            this.recoveredPauseState = true;
            this.updateProgressDisplay();
        });

        this.sound.on('play', () => {
            this.log('Song playing ' + this.sound._src + ' at ' + this.sound.seek());
            this.isDisplayingPaused = false;
            this.recoveredPauseState = false;

            this.recoveredSong = this.sound._src;
            this.recoveredTimestamp = this.sound.seek();
            this.recoveredPauseState = false;

            this.startProgressUpdateInterval();
        });

        this.sound.on('playerror', () => {
            this.displayErrorMessage('Playback error. Please try again.');
            this.log('Playback error ' + this.sound._src + ' at ' + this.sound.seek());
            this.isDisplayingPaused = true;
        });

        this.sound.on('rate', () => {
            this.log('Playback rate altered.');
        });

        this.sound.on('seek', () => {
            this.log('Song seeked ' + this.sound._src + ' to ' + this.sound.seek());

            this.recoveredSong = this.sound._src;
            this.recoveredTimestamp = this.sound.seek();

            if (this.sound.seek() < this.sound._duration - 1)
                this.updateProgressDisplay();

            if (this.sound.seek() > this.sound._duration - 1 && !this.sound.playing()) {
                this.recoveredSong = '';
                this.recoveredTimestamp = 0;
                this.addToSongHistory(this.sound._src);

                this.playNextSong(true);
            }
        });

        this.sound.on('stop', () => {
            this.log('Song stopped ' + this.sound._src + ' at ' + this.sound.seek());

            this.recoveredSong = this.sound._src;
            this.recoveredTimestamp = this.sound.seek();
            this.updateProgressDisplay();
        });

        this.sound.on('end', () => {
            this.log('Song ended ' + this.sound._src + ' at ' + this.sound.seek());

            this.recoveredSong = '';
            this.recoveredTimestamp = 0;
            this.addToSongHistory(this.sound._src);

            this.playNextSong(true);
        });

        this.sound.on('volume', () => {
            this.log('Volume altered.');
        });
    }

    playNextSong(autoplay = true) {
        this.songHistory.forEach((url) => {
            const index = this.unplayedSongs.indexOf(url);
            if (index != -1) {
                this.unplayedSongs.splice(index, 1);
            }

            // this.log('Removed ' + url + ' from unplayed songs.');
        });

        // this.log('Remaining songs: ' + this.unplayedSongs);

        if (this.unplayedSongs.length === 0) {
            this.log('All audio files have been listened to. Restarting.');
            this.unplayedSongs = this.trackList.flat();
            this.songHistory = '';
        }

        const randomIndex = Math.floor(Math.random() * this.unplayedSongs.length)

        this.playSong(this.unplayedSongs[randomIndex], 0, autoplay);
    }

    pauseSong() {
        if (!this.sound)
            return this.playNextSong(false);
        if (!this.sound.playing())
            return this.log('Couldn\'t pause, already stopped');
        this.sound.pause();
    }

    resumeSong() {
        if (!this.sound && this.hasRecoveredSong())
            return this.resumeRecoveredSong();
        if (!this.sound)
            return this.playNextSong(true);
        if (this.sound && this.sound.playing())
            return this.log('Couldn\'t resume, already playing');
        this.sound.play();
    }

    nextSong() {
        if (!this.sound)
            return this.playNextSong(true);
        this.sound.seek(this.sound._duration - 0.1);
    }

    prevSong() {
        const history = this.songHistory;
        if ((this.sound && this.sound.seek() > this.RESTART_VS_PREV_TIMEOUT)) {
            this.log('Seeking to start of song.');
            return this.sound.seek(0);
        }
        if (!history || history.length === 0) {
            this.log('No previous songs to play.');
            return this.sound.seek(0);
        }
        this.log('Playing previous song ' + history[history.length - 1] + ' from ' + history);
        this.playSong(history[history.length - 1], 0, true);
        this.recoveredSong = '';
        this.recoveredTimestamp = 0;
        this.removeFromSongHistory(history[history.length - 1]);

    }


    // Metadata

    updateMetadataDisplay(src) {

        const self = this;

        this.updateDurationDisplay();
        this.mediaTags.read(src, {
            onSuccess: function (tag) {
                if (self.debug)
                    console.log('[RADIO] Found metadata: ', tag);
                var album = tag.tags.album;
                if (tag.tags.year)
                    album += '&nbsp;&nbsp;&nbsp;(' + tag.tags.year + ')';

                self.elements.titleEl.innerHTML = tag.tags.title;
                if (tag.tags.artist)
                    self.elements.titleEl.innerHTML += '&nbsp;&nbsp;—&nbsp;&nbsp;' + tag.tags.artist;

                const albumEl = self.document.createElement('span');
                albumEl.innerHTML = album;
                albumEl.classList.add('radioPart', 'album');
                self.elements.titleEl.appendChild(albumEl);
            },
            onError: function (error) {
                this.displayErrorMessage('No information found for this track.');
                this.log(':(', error.type, error.info);
            }
        });
    }

    updateTitleScroll() {
        // this.log('Resizing title: ' + this.elements.titleWrapperEl.clientWidth < this.elements.titleEl.clientWidth);
        if (this.elements.titleWrapperEl.clientWidth < this.elements.titleEl.clientWidth)
            this.elements.titleEl.classList.add('scrolling');
        else
            this.elements.titleEl.classList.remove('scrolling');
    }

    updateDurationDisplay() {
        if (!this.sound) {
            this.elements.timeEl.textContent = this.elements.timeEl.textContent.split('/')[0] + '/ -:--';
            return;
        }

        const seconds = this.sound._duration;
        var displaySeconds = Math.floor(seconds % 60);
        if (displaySeconds < 10)
            displaySeconds = '0' + displaySeconds;

        this.elements.timeEl.textContent = this.elements.timeEl.textContent.split('/')[0] + '/ ' + Math.floor(seconds / 60) + ':' + displaySeconds;
    }

    updateProgressDisplay() {
        if (!this.sound) {
            this.elements.timeEl.textContent = '-:-- /' + this.elements.timeEl.textContent.split('/')[1];
            return;
        }

        const seconds = this.sound.seek();
        var displaySeconds = Math.floor(seconds % 60);
        if (displaySeconds < 10)
            displaySeconds = '0' + displaySeconds;

        this.elements.timeEl.textContent = Math.floor(seconds / 60) + ':' + displaySeconds + ' /' + this.elements.timeEl.textContent.split('/')[1];
    }

    startProgressUpdateInterval() {
        this.stopProgressInterval();
        this.updateProgressDisplay();
        this.interfaceUpdateIntervalId = setInterval(() => {
            this.recoveredSong = this.sound._src;
            this.recoveredTimestamp = this.sound.seek();
            this.updateProgressDisplay();
        }, this.PROGRESS_UPDATE_INTERVAL);
    }

    stopProgressInterval() {
        clearInterval(this.interfaceUpdateIntervalId);
    }


    // Radio state

    get isRadioOpen() {
        return this.elements.containerEl.classList.contains(this.IS_RADIO_ACTIVE_CLASSNAME);
    }

    set isRadioOpen(bool) {
        if (bool)
            this.elements.containerEl.classList.add(this.IS_RADIO_ACTIVE_CLASSNAME);
        else
            this.elements.containerEl.classList.remove(this.IS_RADIO_ACTIVE_CLASSNAME);
    }

    get isDisplayingPaused() {
        this.elements.containerEl.classList.contains(this.IS_RADIO_PAUSED_CLASSNAME);
    }

    set isDisplayingPaused(bool) {
        if (bool)
            this.elements.containerEl.classList.add(this.IS_RADIO_PAUSED_CLASSNAME);
        else
            this.elements.containerEl.classList.remove(this.IS_RADIO_PAUSED_CLASSNAME);
    }

    // State recovery values

    get songHistory() {
        return (this.window.sessionStorage.getItem(this.SONG_HISTORY_KEY) ?? '').split('\n').map((song) => song.trim()).filter((song) => song !== '');
    }

    set songHistory(str) {
        this.window.sessionStorage.setItem(this.SONG_HISTORY_KEY, str.trim());
    }

    addToSongHistory(song) {
        if (!song)
            return this.log('Tried to add empty song to history.');
        if (this.songHistory.includes(song)) {
            this.log('Tried to add duplicate song to history.');
            this.removeFromSongHistory(song);
        }
        this.songHistory = this.songHistory.join('\n') + '\n' + song.trim();
        this.log('Added ' + song + ' to history: ' + this.songHistory);
    }

    removeFromSongHistory(song) {
        const index = this.songHistory.lastIndexOf(song);
        const history = this.songHistory.flat();
        if (index !== -1) {
            history.splice(index, 1)
            this.songHistory = history.join('\n');
        }
        this.log('Removed ' + song + ' from history: ' + this.songHistory);
    }

    get recoveredSong() {
        return this.window.sessionStorage.getItem(this.RECOVERY_SONG_URL_KEY);
    }

    set recoveredSong(str = '') {
        this.window.sessionStorage.setItem(this.RECOVERY_SONG_URL_KEY, str);
    }

    get recoveredTimestamp() {
        return this.window.sessionStorage.getItem(this.RECOVERY_TIMESTAMP_KEY);
    }

    set recoveredTimestamp(str = '0') {
        this.window.sessionStorage.setItem(this.RECOVERY_TIMESTAMP_KEY, str);
    }

    get recoveredPauseState() {
        return this.window.sessionStorage.getItem(this.RECOVERY_PAUSE_STATE_KEY) === 'true';
    }

    set recoveredPauseState(bool) {
        this.window.sessionStorage.setItem(this.RECOVERY_PAUSE_STATE_KEY, bool);
    }

}