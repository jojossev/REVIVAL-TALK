import { store } from "@/components/store/store";
import "@/styles/globals.css";
import { Provider } from "react-redux";
import { Toaster } from "react-hot-toast";
import PushNotificationLayout from "@/components/firebaseNotification/PushNotification";
import Router from "next/router";
import NProgress from "nprogress";
import { useEffect, useState } from "react";
import axios from "axios";
import Head from "next/head";
import { GET_SETTINGS } from "@/utils/api/api";

// CSS
import "nprogress/nprogress.css";
import "react-loading-skeleton/dist/skeleton.css";

export default function App({ Component, pageProps }) {
  const [favicon, setFavicon] = useState(null);

  // ? Fetch settings ONLY ONCE
  useEffect(() => {
    let isMounted = true;

    const fetchSettings = async () => {
      try {
        const res = await axios.post(
          `${process.env.NEXT_PUBLIC_API_URL}/${process.env.NEXT_PUBLIC_END_POINT}/${GET_SETTINGS}`
        );

        if (isMounted) {
          setFavicon(res.data?.data?.web_setting?.favicon_icon);
        }
      } catch (err) {
        console.error("Settings fetch error:", err);

        // stop infinite retry on 429
        if (err.response?.status === 429) {
          console.warn("Rate limited. Skipping further requests.");
        }
      }
    };

    fetchSettings();

    return () => {
      isMounted = false;
    };
  }, []);

  // ? Service Worker
  useEffect(() => {
    if (typeof window !== "undefined" && "serviceWorker" in navigator) {
      window.addEventListener("load", () => {
        navigator.serviceWorker
          .register("/sw.js")
          .then((reg) => console.log("SW registered:", reg))
          .catch((err) => console.log("SW failed:", err));
      });
    }
  }, []);

  // ? Router events (FIXED memory leak)
  useEffect(() => {
    const start = () => NProgress.start();
    const done = () => NProgress.done();

    Router.events.on("routeChangeStart", start);
    Router.events.on("routeChangeError", done);
    Router.events.on("routeChangeComplete", done);

    return () => {
      Router.events.off("routeChangeStart", start);
      Router.events.off("routeChangeError", done);
      Router.events.off("routeChangeComplete", done);
    };
  }, []);

  return (
    <main>
      {/* ? Dynamic favicon */}
      <Head>
        <link rel="icon" href={favicon || "/favicon.png"} />
      </Head>

      <Provider store={store}>
        <Toaster position="top-center" containerClassName="toast-custom" />
        <PushNotificationLayout>
          <Component {...pageProps} />
        </PushNotificationLayout>
      </Provider>
    </main>
  );
}