(function($) {

    var controller = {
        init: function() {
            if (page == "store") viewStore.init();
            if (page == "retrieve") viewRetrieve.init();
        }
    };

    var viewStore = {
        init: function() {
            // set variables
            this.$form = $("#surv-form");
            this.$anmOther = $("#anm-other");
            this.$anmInput = $("#anm-input");

            this.render();
        },

        render: function() {
            // focus input "other" on click radio button "other"
            this.$anmOther.on("click", function() {
                viewStore.$anmInput.focus();
            });

            // check radio button "other" on click input "other"
            this.$anmInput.on("click", function() {
                viewStore.$anmOther.prop("checked", true);
            });

            // change radio input value on change input text
            this.$anmInput.on("keyup", function() {
                // capitalize only first letter
                let otherAnimal = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();

                viewStore.$anmOther.val(otherAnimal);
            });

            // add event listener on form submit
            this.$form.submit(function(event) {
                event.preventDefault();

                $.post(a01034486_submit.ajax_url, {         // POST request
                    _ajax_nonce: a01034486_submit.nonce,    // nonce
                    action: "survey_animal_form_submit",    // action
                    data: $(this).serialize()               // data
                }, function(response) {                     // callback
                    if (response.success == false) {        // response: array("success" => true/false, "data" => ...);
                        // show error message
                        alert(`Server message:\n\n
                            ${response.data}`);
                    } else if (response.success == true) {
                        // show success message
                        var r = confirm(`Server message:\n\n
                            Thank's ${response.data}!\n
                            Press \"OK\" to redirect to \"results\" page, or \"Cancel\" to submit again.`);

                        // redirect
                        if (r == true) {
                            location.href = "results";
                        }
                    } else {
                        // connection error message
                        alert(`Something went wrong.\n\nPlease verify your connection and try again, or contact the server administrator.`);
                    }
                });
            });

            // add event listener on form reset
            this.$form.on("reset", function() {
                // clear radio button and input from "other animal"
                viewStore.$anmOther.prop("checked", false);
                viewStore.$anmOther.val("");
                viewStore.$anmInput.val("");
            })
        }
    };

    var viewRetrieve = {
        init: function() {
            // set variables
            this.$form = $("#clear-form");

            this.render();
        },

        render: function() {
            // add event listener on form submit
            this.$form.submit(function(event) {
                event.preventDefault();

                var r = confirm(`WARNING!\n\n
                    All data will be deleted!\n
                    Press \"OK\" to confirm.`);

                if (r == true) {
                    $.post(a01034486_submit.ajax_url, {         // POST request
                        _ajax_nonce: a01034486_submit.nonce,    // nonce
                        action: "survey_animal_clear_data",     // action
                        data: $(this).serialize()               // data
                    }, function(response) {                     // callback
                        if (response.success == false) {        // response: array("success" => true/false, "data" => ...);
                        // show error message
                        alert(`Server message:\n\n
                        ${response.data}`);
                        } else if (response.success == true) {
                            // show success message
                            alert(`Server message:\n\n
                            ${response.data}`);

                            // reloads the current page from the server
                            location.reload(true);
                        } else {
                            // connection error message
                            alert(`Something went wrong.\n\nPlease verify your connection and try again, or contact the server administrator.`);
                        }
                    });
                }
            });
        }
    }

    controller.init();

})(jQuery);

// org retrieve php
// org store js
// tratamento animal other (espaços)
// chart... efeito