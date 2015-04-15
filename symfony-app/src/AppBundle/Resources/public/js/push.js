(function($, undefined) {
    'use strict';

    var isPushEnabled = false;
    var serviceWorkerRegistration = null;

    /*
     * Send subscription to server
     */
    var sendSubscriptionToServer = function(subscription) {
        log('TODO: Implement sendSubscriptionToServer()');
        log(subscription);
    };

    /*
     * Unsubscribe
     */
    var unsubscribe = function() {
        $('.js-push-button').attr('disabled', true);

        serviceWorkerRegistration.pushManager.getSubscription().then(function(pushSubscription) {
            if (!pushSubscription) {
                isPushEnabled = false;
                $('.js-push-button').attr('disabled', false);
                $('.js-push-button .js-label').text($('.js-push-button').data('label-enable'));
                return;
            }

            var subscriptionId = pushSubscription.subscriptionId;

            // TODO: Make a request to your server to remove
            // the subscriptionId from your data store so you
            // don't attempt to send them push messages anymore

            pushSubscription.unsubscribe().then(function(successful) {
                $('.js-push-button').attr('disabled', false);
                $('.js-push-button .js-label').text($('.js-push-button').data('label-enable'));
                isPushEnabled = false;
            }).catch(function(e) {
                log('Unsubscription error: ', e);
                $('.js-push-button').attr('disabled', false);
            });
        }).catch(function(e) {
            log('Error thrown while unsubscribing from push messaging.', e);
        });
    };

    /*
     * Subscribe
     */
    var subscribe = function() {
        $('.js-push-button').attr('disabled', true);

        serviceWorkerRegistration.pushManager.subscribe().then(function(subscription) {
            isPushEnabled = true;
            $('.js-push-button .js-label').text($('.js-push-button').data('label-disable'));
            $('.js-push-button').attr('disabled', false);

            return sendSubscriptionToServer(subscription);
        }).catch(function(e) {
            if (Notification.permission === 'denied') {
                log('Permission for Notifications was denied');
                $('.js-push-button').attr('disabled', true);
            } else {
                log('Unable to subscribe to push.', e);
                $('.js-push-button').attr('disabled', false);
                $('.js-push-button .js-label').text($('.js-push-button').data('label-enable'));
            }
        });
    };

    /*
     * Initializes the state of the buttons.
     */
    var initState = function(reg) {
        serviceWorkerRegistration = reg;

        if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
            log('Notifications aren\'t supported.');
            return;
        }

        if (Notification.permission === 'denied') {
            log('The user has blocked notifications.');
            return;
        }

        if (!('PushManager' in window)) {
            log('Push messaging isn\'t supported.');
            return;
        }

        serviceWorkerRegistration.pushManager.getSubscription().then(function(subscription) {
            $('.js-push-button').attr('disabled', false);

            if (!subscription) {
                return;
            }

            sendSubscriptionToServer(subscription);

            $('.js-push-button .js-label').text($('.js-push-button').data('label-disable'));
            isPushEnabled = true;
        }).catch(function(err) {
            log('Error during getSubscription()', err);
        });
    };

    /*
     * Util for logging
     */
    var log = function(arg1, arg2) {
        console.log && console.log(arg1, arg2);
    };

    $(function() {
        $('.js-push-button').bind('click', function() {
            (isPushEnabled ? unsubscribe : subscribe)();
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/js/push-service-worker.js').then(initState);
        } else {
            log('Service workers are not supported in this browser.');
        }
    });
})(jQuery);
