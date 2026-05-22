"use client";
import { useEffect, useState } from "react";
import { notificationRequestSelector, setPermissionRequest } from "../store/reducers/CheckPermissionsReducer";
import { translate } from "@/utils/translation";
import { useSelector } from "react-redux";
import toast from "react-hot-toast";

const NotificationPrompt = () => {

  const notificationPermission = useSelector(notificationRequestSelector);

  const [show, setShow] = useState(false);

  useEffect(() => {

    const timer = setTimeout(() => {
      if (
        Notification.permission !== "granted" && notificationPermission !== "rejected") {
        setShow(true);
      }
    }, 20000);

    return () => clearTimeout(timer);
  }, []);

  const handleAllow = async () => {
    try {
      const permissionRes = await Notification.requestPermission();
      // console.log("permissionRes", permissionRes)
      setPermissionRequest({ requestRes: permissionRes })
      setShow(false);
    } catch (error) {
      console.error("Permission error:", error);
    }
  };

  const handleLater = () => {
    setPermissionRequest({ requestRes: "rejected" })
    setShow(false);
    toast(translate('notificationBlocked'), {
      icon: '🔕',
      position: 'top-left',
    });
  };

  if (!show) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

      <div className="fixed top-6 left-6 bg-white shadow-xl rounded-xl p-4 w-80 z-50 dark:text-black">
        <h4 className="font-semibold mb-2">📢 {translate('dontMissUpdate')}</h4>
        <p className="text-sm mb-3 font-medium">
          {translate('allNotification')}
        </p>
        <div className="flex gap-2">
          <button
            onClick={handleAllow}
            className="secondaryBg text-white px-3 py-1 rounded-md"
          >
            {translate('enableNotifications')}
          </button>
          <button
            onClick={handleLater}
            className="border px-3 py-1 rounded-md dark:text-black"
          >
            {translate('maybeLater')}
          </button>
        </div>
      </div>
    </div>
  );
};

export default NotificationPrompt;
