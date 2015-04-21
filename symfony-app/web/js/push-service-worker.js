'use strict';

self.console.log("SW startup");

self.addEventListener('push', function(event) {
    self.registration.pushManager.getSubscription().then(function(pushSubscription) {
        if (pushSubscription) {
            var apiUrl = '/gcm/get/' + encodeURI(pushSubscription.subscriptionId);

            event.waitUntil(
                fetch(apiUrl).then(function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        throw new Error();
                    }

                    return response.json().then(function(data) {
                        var n = data.notifications;
                        for (var i = 0; i < n.length; i++) {
                            var notification = n[i];
                            self.registration.showNotification(notification.subject, {
                                body: notification.msg,
                                icon: '/apple-touch-icon.png',
                                tag: 'notification-alert'
                            });
                        }
                    });
                }).catch(function(err) {
                    var title = 'An error occurred';
                    var message = 'We were unable to get the information for this push message';
                    return self.registration.showNotification(title, {
                        body: message,
                        icon: '/apple-touch-icon.png',
                        tag: 'notification-error'
                    });
                })
            );
        }
    });
});


self.addEventListener('notificationclick', function(event) {
    self.console.log('On notification click: ', event.notification.tag);
    event.notification.close();

    event.waitUntil(clients.matchAll({
        type: "window"
    }).then(function(clientList) {
    for (var i = 0; i < clientList.length; i++) {
        var client = clientList[i];
        if (client.url == '/' && 'focus' in client) {
            return client.focus();
        }
    }
    if (clients.openWindow)
        return clients.openWindow('/');
    }));
});
