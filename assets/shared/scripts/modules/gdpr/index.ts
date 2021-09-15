import Gdpr from './Gdpr';
import GoogleAnalytics from './GoogleAnalytics';

/**
 * The "Entrypoint".
 */
window.addEventListener('load', () => {
    const banner: HTMLElement = document.querySelector('#gdpr-module');
    if (banner) {
        const gdpr = new Gdpr();
        gdpr.addTracker('g-analytics', new GoogleAnalytics('PASTE_YOUR_KEY'))
            .start();

        addTrackersToBannerConfig(banner, gdpr);
        addEventsToBanner(banner, gdpr);

        if(!gdpr.isGDPRStorageItemExists()) {
            toggleBanner(banner, true);
        }
    }
});

/**
 * Build and append the trackers checkboxes to the configuration section of the banner.
 *
 * @param banner The banner on which the the checkboxes will be added.
 * @param gdpr The gdpr object which contains the trackers.
 */
const addTrackersToBannerConfig = (banner: HTMLElement, gdpr: Gdpr) => {
    let trackersCheckboxesContainer: HTMLElement = banner.querySelector<HTMLElement>('#gdpr-trackers');
    gdpr.getTrackers().forEach((tracker: Tracker, slug: string) => {
        trackersCheckboxesContainer.append(
            buildCheckbox(
                slug,
                tracker.getLabel(),
                gdpr.isTrackerAccepted(slug),
            )
        );
    });
}


/**
 * Add events to the banner.
 *
 * The event are primarily applied to the buttons, to handle their clicks.
 *
 * It also add an event to an #openGdpr element anywhere on the document to toggle the display of the banner.
 *
 * @param banner The banner on which the event are applied.
 * @param gdpr
 */
const addEventsToBanner = (banner: HTMLElement, gdpr: Gdpr) => {
    banner.querySelector<HTMLButtonElement>('#accept-btn').addEventListener(
        'click',
        () => handleAcceptAll(banner, gdpr)
    );

    banner.querySelector<HTMLButtonElement>('#refuse-gdpr').addEventListener(
        'click',
        () => handleDenyAll(banner, gdpr)
    );

    banner.querySelector<HTMLButtonElement>('#config-btn').addEventListener(
        'click',
        () => toggleConfigSection(banner.querySelector<HTMLElement>('#gdpr-config'))
    );

    banner.querySelector<HTMLButtonElement>('#valid-config-gdpr').addEventListener(
        'click',
        () => handleConfigValidation(banner, gdpr)
    );

    let openGdprBtn: HTMLElement = document.getElementById('openGdpr');
    if(openGdprBtn) {
        openGdprBtn.addEventListener('click', (e: MouseEvent) => {
            e.preventDefault();
            toggleBanner(banner, true);
        });
    }
}

/**
 * Toggle the banner.
 * The function only change the display style value of the element to 'block' when displayed
 * and to 'none' when hidden.
 *
 * @param banner
 * @param show
 */
const toggleBanner = (banner: HTMLElement, show: boolean = null) => {
    if (show) {
        banner.style.display = 'block';
    } else {
        banner.style.display = 'none';
    }
}

/**
 * Toggle the configuration section of the banner.
 *
 * @param configSection The element to toggle.
 */
const toggleConfigSection = (configSection: HTMLElement) => {
    if (configSection.style.display == 'none') {
        configSection.style.display = 'block';
    } else {
        configSection.style.display = 'none';
    }
}

/**
 * Define the handler when the acceptAll button of the banner is clicked.
 *
 * @param gdpr The gdpr module.
 * @param banner The banner where the button is located.
 */
const handleAcceptAll = (banner: HTMLElement, gdpr: Gdpr) => {
    gdpr.setAccepted(true);
    gdpr.acceptAllTrackers();
    gdpr.applyTrackers();
    toggleBanner(banner, false);
}

/**
 * Define the handler when the acceptAll button of the banner is clicked.
 *
 * @param gdpr The gdpr module.
 * @param banner The banner where the button is located.
 */
const handleDenyAll = (banner: HTMLElement, gdpr: Gdpr) => {
    gdpr.setAccepted(false);
    toggleBanner(banner, false);
}

/**
 * Define the handler when the acceptAll button of the banner is clicked.
 *
 * @param gdpr The gdpr module.
 * @param banner The banner where the button is located.
 */
const handleConfigValidation = (banner: HTMLElement, gdpr: Gdpr) => {
    gdpr.clearStorage();
    gdpr.setAccepted(true);
    const trackerCheckBoxes: NodeListOf<HTMLInputElement> = banner.querySelectorAll<HTMLInputElement>('#gdpr-trackers input[type="checkbox"]');
    if (trackerCheckBoxes && trackerCheckBoxes.length > 0) {
        trackerCheckBoxes.forEach((checkbox) => {
            if (checkbox.checked) {
                gdpr.acceptTracker(checkbox.value);
            }
        });
    }
    gdpr.applyTrackers();
    toggleBanner(banner, false);
}

/**
 * Build a checkbox HTMLInputElement based on an html template.
 *
 * @param value The value of the checkbox element.
 * @param label The label text.
 * @param checked The checkbox should be checked or not.
 */
const buildCheckbox = (value: string, label: string, checked: boolean) => {
    let template: HTMLElement = <HTMLElement>document.getElementById('trackerCheckboxTemplate').cloneNode(true);

    if (template) {
        template.setAttribute('id', value);
        let checkbox: HTMLInputElement = template.querySelector<HTMLInputElement>('input[type="checkbox"]');
        checkbox.value = value;
        checkbox.checked = checked;
        let labelSpan: HTMLSpanElement = template.querySelector<HTMLSpanElement>('.tracker-label');
        labelSpan.innerHTML = label;
    }

    template.style.display = 'block';

    return template;
}
