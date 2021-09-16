// USED TO SUBMIT FORM FROM CRUDS
let submitFormButtons = document.querySelectorAll('[data-btn-submit-form]');
submitFormButtons.forEach((submitFormButton) => {
    submitFormButton.addEventListener('click', () => {

        let form = null;
        if (submitFormButton.getAttribute('data-btn-submit-form') != '') {
            let selector = "#" + submitFormButton.getAttribute('data-btn-submit-form');
            form = document.querySelector(selector);
        } else {

            let forms = document.querySelectorAll('form');

            forms.forEach((formElement) => {
                if (!form) {
                    if (!formElement.hasAttribute('data-form-delete-model')) {
                        console.log('je rentre');
                        form = formElement;
                    }
                }
            })

        }
        if (form !== null) {
            console.log(form);
            form.submit();
        }
    })
})