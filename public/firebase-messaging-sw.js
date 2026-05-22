importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js')
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js')
// // Initialize the Firebase app in the service worker by passing the generated config 

const firebaseConfig = {
  apiKey: "AIzaSyBueNQo0qKxQv3e9pJMuTPyZoxWFj_qIhU",
  authDomain: "etalk-64fa2.firebaseapp.com",
  projectId: "etalk-64fa2",
  storageBucket: "etalk-64fa2.firebasestorage.app",
  messagingSenderId: "241437792330",
  appId: "1:241437792330:web:3d282f07533ebab94ceeb3",
  measurementId: "G-2KEEVBR5HJ"
}

firebase?.initializeApp(firebaseConfig)

// Retrieve firebase messaging
const messaging = firebase.messaging();

self.addEventListener('install', function (event) {
  // console.log('Hello world from the Service Worker :call_me_hand:');
});

// Handle background messages
self.addEventListener('push', function (event) {
  const payload = event.data.json();
  const notificationTitle = payload.notification.body;

  const notificationOptions = {
    body: payload.notification.body,
    icon: payload.data?.image || './favicon.png',
    data: {
      url: `/${payload.data?.language_code}/news/${payload.data?.news_slug}`,
      id: payload.data?.news_id,
    },
  };

  event.waitUntil(
    self.registration.showNotification(notificationTitle, notificationOptions)
  );
});


// 🔥 ADDED CLICK LOGIC (Nothing else changed)
self.addEventListener('notificationclick', function (event) {
  event.notification.close();

  const urlToOpen = event.notification.data?.url;

  if (!urlToOpen) return;

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        for (const client of clientList) {
          if (client.url.includes(self.location.origin) && 'focus' in client) {
            client.navigate(urlToOpen);
            return client.focus();
          }
        }

        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
  );
});