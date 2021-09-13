import {tns} from 'tiny-slider/src/tiny-slider';
import {TinySliderSettings} from 'tiny-slider/src/tiny-slider';

let defaultOptions: TinySliderSettings = {
    items: 1,
    nav: true,
    autoplayButtonOutput: false,
    mouseDrag: true,
    autoplay: true,
    navPosition: 'bottom',
    gutter: 40,
    controlsContainer: "#customize-controls",
    "responsive": {
        "992": {
            "items": 3
        },
        "768": {
            "items": 2
        },
        "576": {
            "items": 1
        }
    }
};

let partnerOptions: TinySliderSettings = {
    items: 1,
    nav: false,
    autoplayButtonOutput: false,
    mouseDrag: true,
    controls: false,
    autoplay: true,
    gutter: 20,
    navPosition: 'bottom',
    "responsive":{
        "992":{
            "items":5
        },
        "768":{
            "items":3
        },
        "576":{
            "items":1
        }
    }
};

let imgOptions: TinySliderSettings = {
    items: 1,
    autoplayButtonOutput: false,
    mouseDrag: true,
    controls: false,
    autoplay: true,
    navAsThumbnails: true,
    nav: true,
    navContainer: "#customize-thumbnails",
};


export function initSlider($section = document.body) {
    // @ts-ignore
    for (const $element of $section.querySelectorAll<HTMLElement>('[data-slider]')) {
        let options: TinySliderSettings = {};
        try {
            options = JSON.parse($element.getAttribute('data-slider'));
        } catch (e) {
            console.error(e);
        }

        options = Object.assign({}, defaultOptions, options);
        options.container = $element;

        const packSlider = tns(options);
    }

    // @ts-ignore
    for (const $element of $section.querySelectorAll<HTMLElement>('[data-slider-img]')) {
        let options: TinySliderSettings = {};
        try {
            options = JSON.parse($element.getAttribute('data-slider-img'));
        } catch (e) {
            console.error(e);
        }

        options = Object.assign({}, imgOptions, options);
        options.container = $element;

        const packSlider = tns(options);
    }

    // @ts-ignore
    for (const $element of $section.querySelectorAll<HTMLElement>('[data-slider-partner]')) {
        let options: TinySliderSettings = {};
        try {
            options = JSON.parse($element.getAttribute('data-slider-partner'));
        } catch (e) {
            console.error(e);
        }

        options = Object.assign({}, partnerOptions, options);
        options.container = $element;

        const packSlider = tns(options);
    }
}

window.addEventListener('load', () => {
    initSlider();
});
