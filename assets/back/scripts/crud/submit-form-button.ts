// USED TO SUBMIT FORM FROM CRUDS
let submitFormButtons = document.querySelectorAll('[data-btn-submit-form]');
submitFormButtons.forEach((submitFormButton) => {
    submitFormButton.addEventListener('click', () => {
        let form = null;
        if (submitFormButton.getAttribute('data-btn-submit-form') != '') {
            let selector = "#" + submitFormButton.getAttribute('data-btn-submit-form');
            form = document.querySelector(selector);
        } else {
            form = document.querySelector('form');
        }
        if (form !== null) {
            form.submit();
        }
    })
})