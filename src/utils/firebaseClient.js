'use client';

import { initializeApp, getApps, getApp } from 'firebase/app';
import { getAuth } from 'firebase/auth';
import firebase from 'firebase/compat/app';

const getFirebaseConfig = () => ({
  apiKey: process.env.NEXT_PUBLIC_API_KEY,
  authDomain: process.env.NEXT_PUBLIC_AUTH_DOMAIN,
  projectId: process.env.NEXT_PUBLIC_PROJECT_ID,
  storageBucket: process.env.NEXT_PUBLIC_STORAGE_BUCKET,
  messagingSenderId: process.env.NEXT_PUBLIC_MESSAGING_SENDER_ID,
  appId: process.env.NEXT_PUBLIC_APP_ID,
  measurementId: process.env.NEXT_PUBLIC_MEASUREMENT_ID,
});

export const isFirebaseConfigured = () => {
  const apiKey = process.env.NEXT_PUBLIC_API_KEY;
  return (
    typeof apiKey === 'string' &&
    apiKey.trim().length > 10 &&
    apiKey !== 'undefined'
  );
};

export const isBrowser = () => typeof window !== 'undefined';

let firebaseAppInstance = null;
let firebaseAuthInstance = null;

export const getFirebaseApp = () => {
  if (!isBrowser() || !isFirebaseConfigured()) {
    return null;
  }

  if (!firebaseAppInstance) {
    const firebaseConfig = getFirebaseConfig();

    if (!firebase.apps.length) {
      firebase.initializeApp(firebaseConfig);
    }

    firebaseAppInstance = getApps().length
      ? getApp()
      : initializeApp(firebaseConfig);
  }

  return firebaseAppInstance;
};

export const getFirebaseAuth = () => {
  if (!isBrowser() || !isFirebaseConfigured()) {
    return null;
  }

  const app = getFirebaseApp();
  if (!app) {
    return null;
  }

  if (!firebaseAuthInstance) {
    firebaseAuthInstance = getAuth(app);
  }

  return firebaseAuthInstance;
};
