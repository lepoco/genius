// https://developers.google.com/web/ilt/pwa/introduction-to-push-notifications

// if ("Notification" in window && navigator.serviceWorker) {
//   // Display the UI to let the user toggle notifications
// }

// if (Notification.permission === "granted") {
//   /* do our magic */
// } else if (Notification.permission === "blocked") {
//   /* the user has previously denied push. Can't reprompt. */
// } else {
//   /* show a prompt to the user */
// }

// const NOTIFY_ACCEPT_BUTTON = document.querySelector(".btn-notify");

// if (!NOTIFY_ACCEPT_BUTTON) {
//   return;
// }

// NOTIFY_ACCEPT_BUTTON.addEventListener("click", function () {
//   let promise = Notification.requestPermission();
//   // wait for permission
//   console.log(promise);
// });

// if (Notification.permission == "granted") {
//   navigator.serviceWorker.getRegistration().then(function (reg) {
//     var options = {
//       body: "Here is a notification body!",
//       icon: "https://polkryptex.lan/img/icons/192.png?v=1.1.0",
//       vibrate: [100, 50, 100],
//       data: {
//         dateOfArrival: Date.now(),
//         primaryKey: 1,
//       },
//     };

//     reg.showNotification("Hello world!", options);
//   });
// }
