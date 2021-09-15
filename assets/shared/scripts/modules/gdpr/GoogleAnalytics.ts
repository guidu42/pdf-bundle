/**
 * Define the Google analytics tracker.
 */
export default class GoogleAnalytics implements Tracker {

    /**
     * The key required to make the analytics working.
     * @private
     */
    private key;

    constructor(key: string) {
        this.key = key;
    }

    track(): void {
        const script = document.createElement('script');
        script.src = 'https://www.googletagmanager.com/gtag/js?id='+this.key;
        script.addEventListener('load', () => {
            (window as any).dataLayer = (window as any).dataLayer || [];

            function gtag(...args: any[]) {
                (window as any).dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', this.key);
        });

        document.querySelector('body').appendChild(script);
    }

    untrack(): void {    }

    getLabel(): string {
        return "Google Analytics";
    }
}
