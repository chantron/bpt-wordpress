(function() {
'use strict';

var $ = jQuery,
    getAccount;

var getAccount = function getAccount() {
    $('.bpt-loading').fadeIn();

    $.ajax(
        bptSetupWizardAjax.ajaxurl,
        {
            type: 'POST',
            data: {
                // wp ajax action
                action : 'bpt_test_account',
                // vars
                devID : $('input[name="_bpt_dev_id"]').val(),
                clientID : $('input[name="_bpt_client_id"]').val(),
                // send the nonce along with the request
                nonce: bptSetupWizardAjax.nonce,
            },
            accepts: 'json',
            dataType: 'json'

        }
    )
    .always(function() {
        $('.bpt-loading').hide();
    })
    .fail(function(data) {
        bptSetupWizard.set({
            unknownError: data
        });
    })
    .done(function(data) {

        if ( data.error === 'No Developer ID.') {
            bptSetupWizard.set({
                accountError: data.error
            });

            return;
        }

        if (data.account.result || data.events.result) {

            if (data.account.result) {
                bptSetupWizard.set({
                    accountError: data.account
                });
            }

            if (data.events.result) {
                bptSetupWizard.set({
                    eventError: data.events
                });
            }

            if (!data.events.result) {

                bptSetupWizard.set({
                    events: data.events
                });

            } else {

                bptSetupWizard.set({
                    events: undefined
                });

            }

            if (!data.account.result) {
                bptSetupWizard.set({
                    account: data.account,
                });
            } else {
                bptStupWizard.set({
                    account: undefined,
                });
            }

            return;
        }

        bptSetupWizard.set({
            account: data.account,
            events: data.events,
            accountError: undefined,
            eventError: undefined
        });

    })
    .always(function() {

    });

};

})(jQuery);
