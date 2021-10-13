// @ts-ignore
import slugify from 'slugify';

/**
 * This class is the base of the Gdpr module.
 */
export default class Gdpr {
    private static readonly GDPR_STORAGE_KEY: string = "gdpr-accepted";

    /**
     * The prefix used for any element added to the user's browser storage.
     * @private
     */
    private gdprStoragePrefix: string = "gdpr-";

    /**
     * Value to know if the Gdpr module has been accepted.
     * @private
     */
    private accepted: boolean = false;

    /**
     * List of all
     */
    private trackers: Map<string, Tracker> = new Map<string, Tracker>();

    /**
     * Create the Gdpr module.
     * Set the accepted value by checking if the main gdpr local storage item exists.
     */
    constructor() {
        this.accepted = localStorage.getItem(Gdpr.GDPR_STORAGE_KEY) === 'yes';
    }

    /**
     * Start the Gdpr Module.
     *
     * Iterate over all the trackers available in the Gdpr module
     *
     */
    start(): void {
        if (this.isGDPRStorageItemExists()) {
            if (this.isAccepted) {
                this.applyTrackers();
            }
            return;
        }
    }


    /**
     * Clear all possible storage item added by this module.
     */
    clearStorage(): this {
        localStorage.removeItem(Gdpr.GDPR_STORAGE_KEY);

        for (const trackerSlug in this.trackers) {
            localStorage.removeItem(this.gdprStoragePrefix+trackerSlug);
        }

        return this;
    }

    /**
     * Check the current acceptation state of the gdpr.
     */
    isAccepted(): boolean {
        return this.accepted;
    }

    /**
     * Set the status of the Gdpr acceptation.
     *
     * If the Gdpr is accepted by the user.
     * A local storage item named after a constant (see GDPR_STORAGE_KEY) is added with the value 'yes'.
     *
     * Otherwise, the local storage item is added or updated with the value 'no' and
     * if any trackers have already been accepted all their corresponding local storage items are removed.
     *
     * @param status
     */
    setAccepted(status: boolean): this {
        if (status) {
            this.accepted = true;
            localStorage.setItem(Gdpr.GDPR_STORAGE_KEY, 'yes');
        } else {
            this.accepted = false;
            localStorage.setItem(Gdpr.GDPR_STORAGE_KEY, 'no');
            this.trackers.forEach((tracker: Tracker, slug: string) => {
                tracker.untrack();
                localStorage.removeItem(this.gdprStoragePrefix+slug);
            });
        }

        return this;
    }

    /**
     * Get the trackers lists of the Gdpr Module.
     */
    getTrackers(): Map<string, Tracker> {
        return this.trackers;
    }

    /**
     * Add a tracker to the Gdpr Module trackers list.
     *
     * To prevent any un-slugified name to be added, an additional slugifycation is processed.
     *
     * @param slugName The slugified name of the tracker
     * @param tracker The tracker to add, must implement the Tracker and Labeled interfaces.
     */
    addTracker(slugName: string, tracker: Tracker): Gdpr {
        this.trackers.set(slugify(slugName), tracker);

        return this;
    }

    /**
     * Remove a tracker from the Gdpr Module trackers list.
     *
     * @param slugName The slugified name of the
     */
    removeTracker(slugName: string): Gdpr {
        let slug = slugify(slugName);
        this.trackers.get(slug).untrack();
        this.trackers.delete(slug);

        return this;
    }

    /**
     * Loop through all the trackers and add a local storage item for each tracker.
     *
     * The created local storage item is defined like this :
     * name : TRACKER_SLUG
     * Value: yes
     *
     * Only occur if the gdpr module is accepted.
     */
    acceptAllTrackers() {
        if (this.accepted) {
            this.trackers.forEach(
                (tracker: Tracker, slug: string) => localStorage.setItem(this.gdprStoragePrefix+slug, 'yes')
            );
        }
    }

    /**
     * Accept a single tracker, the one matching the specified slug.
     *
     * This create a local storage item for the tracker, which is defined like :
     * name : TRACKER_SLUG
     * Value: yes
     *
     * Only occur if the gdpr module is accepted.
     *
     * @param trackerSlug
     */
    acceptTracker(trackerSlug: string) {
        if (this.accepted) {
            let slug = slugify(trackerSlug);
            if (this.trackers.has(slug)) {
                localStorage.setItem(this.gdprStoragePrefix+slug, 'yes');
            }
        }
    }

    /**
     * Loop through all the trackers and call their track() method if their are accepted.
     */
    applyTrackers(): void {
        if (this.accepted) {
            this.trackers.forEach((tracker: Tracker, slug: string ) => {
                if (this.isTrackerAccepted(slug)) {
                    tracker.track();
                }
            });
        }
    }

    /**
     * Check if the main local storage item of the module exists.
     * @private
     */
    isGDPRStorageItemExists(): boolean {
        return localStorage.getItem(Gdpr.GDPR_STORAGE_KEY) !== null;
    }

    /**
     * Check if a tracker has been accepted by the user.
     * @param trackerSlug
     */
    isTrackerAccepted(trackerSlug: string) {
        const localStorageItem = localStorage.getItem(this.gdprStoragePrefix+slugify(trackerSlug));
        return localStorageItem !== null && localStorageItem === 'yes';
    }
}
