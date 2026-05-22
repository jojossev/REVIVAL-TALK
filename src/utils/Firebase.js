'use client'
import { getMessaging, getToken, onMessage, isSupported } from 'firebase/messaging'
import toast from 'react-hot-toast';
import { useDispatch, useSelector } from 'react-redux';
import { loadFcmToken } from '@/components/store/reducers/settingsReducer';
import { checkNotificationPermission, notificationRequestSelector } from '@/components/store/reducers/CheckPermissionsReducer';
import { useEffect, useState } from 'react';
import { getFirebaseApp, getFirebaseAuth, isFirebaseConfigured } from './firebaseClient';


const FirebaseData = () => {

  const dispatch = useDispatch()
  const [authentication, setAuthentication] = useState(null)

  const notificationPermission = useSelector(notificationRequestSelector);

  useEffect(() => {
    setAuthentication(getFirebaseAuth())
  }, [])

  const messagingInstance = async () => {
    if (!isFirebaseConfigured()) {
      return null
    }

    try {
      const firebaseApp = getFirebaseApp()
      if (!firebaseApp) {
        return null
      }

      const isSupportedBrowser = await isSupported();
      if (isSupportedBrowser) {
        return getMessaging(firebaseApp);
      }
    } catch (err) {
      console.error('Error checking messaging support:', err);
      return null;
    }

    return null
  };

  const fetchToken = async (setTokenFound = () => { }, setFcmToken = () => { }) => {
    const messaging = await messagingInstance();
    if (!messaging) {
      return;
    }

    try {
      const permission = notificationPermission;
      if (permission === 'granted') {
        dispatch(checkNotificationPermission({ data: { isNotificationPermission: 'granted' } }))
        getToken(messaging, {
          vapidKey: process.env.NEXT_PUBLIC_VAPID_KEY,
        })
          .then((currentToken) => {
            if (currentToken) {
              setTokenFound(true);
              loadFcmToken(currentToken);
              setFcmToken(currentToken);
            } else {
              setTokenFound(false);
              setFcmToken(null);
              toast.error('Permission is required to receive notifications.');
            }
          })
          .catch((err) => {
            console.error('Error retrieving token:', err);
            if (err.message.includes('no active Service Worker')) {
              registerServiceWorker(setTokenFound, setFcmToken);
            }
          });
      } else if (permission === 'denied') {
        dispatch(checkNotificationPermission({ data: { isNotificationPermission: 'denied' } }))
      } else {
        setTokenFound(false);
        setFcmToken(null);
      }
    } catch (err) {
      console.error('Error requesting notification permission:', err);
    }
  };

  useEffect(() => {
    if (notificationPermission && isFirebaseConfigured()) {
      fetchToken();
    }
  }, [notificationPermission]);

  const registerServiceWorker = (setTokenFound, setFcmToken) => {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker
        .register('/firebase-messaging-sw.js')
        .then((registration) => {
          console.log('Service Worker registration successful with scope: ', registration.scope);
          fetchToken(setTokenFound, setFcmToken);
        })
        .catch((err) => {
          console.log('Service Worker registration failed: ', err);
        });
    }
  };

  const onMessageListener = async () => {
    const messaging = await messagingInstance();
    if (messaging) {
      return new Promise((resolve) => {
        onMessage(messaging, (payload) => {
          resolve(payload);
        });
      });
    }

    return null;
  };

  const signOut = () => {
    const auth = authentication || getFirebaseAuth()
    if (!auth) {
      return Promise.resolve()
    }
    return auth.signOut();
  };

  return { authentication, fetchToken, onMessageListener, signOut }
}

export default FirebaseData;
