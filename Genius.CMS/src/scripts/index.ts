/**
 * Genius
 * https://github.com/lepoco/Genius
 *
 * GPL-3.0 https://github.com/lepoco/Genius/blob/main/LICENSE
 */

import Forms from "./common/forms";
import Cookie from "./common/cookie";
import SignOut from "./common/signout";

let appData = (window as any).app;

require("./../sass/style.scss");

if ("serviceWorker" in navigator) {
  window.addEventListener("load", () => {
    navigator.serviceWorker
      .register("https://genius.lan/service-worker.js")
      .then((registration) => {
        if (appData.props.debug) {
          console.log("SW registered: ", registration);
        }

        //registration.pushManager.subscribe({userVisibleOnly: true, applicationServerKey: "71562645621"});
      })
      .catch((registrationError) => {
        if (appData.props.debug) {
          console.log("SW registration failed: ", registrationError);
        }
      });
  });
}

new Forms();
new Cookie();
new SignOut();

try {
  require("./pages/" + appData.props.view);
  appData.routing = { success: true, message: "imported" };
} catch (error) {
  appData.routing = {
    success: false,
    message: "No module for page " + appData.props.view,
    error: error.message,
  };
}

if (!window.navigator.onLine) {
  document.body.classList.add("--offline");
}

if (appData.props.debug) {
  console.debug("window.app", appData);
  console.debug("Connection online", window.navigator.onLine);
}
