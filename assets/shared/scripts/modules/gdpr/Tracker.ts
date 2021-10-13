/**
 * Define the required behavior of a tracker.
 */
interface Tracker {
    /**
     * Function to define the behavior of a tracker when it gets activated.
     */
    track(): void;

    /**
     * Function to define the behavior of a tracker when it gets deactivated.
     */
    untrack(): void;

    /**
     * The label of the tracker.
     */
    getLabel(): string;
}
