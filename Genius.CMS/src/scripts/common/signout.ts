import * as Bootstrap from "./../components/bootstrap-bundle";

export const name = "SignOut";

export default class SignOut {
  timeout:number;

  constructor() {
    if (!(window as any).app.auth.loggedIn) {
      return;
    }

    this.setTimeout();
    this.startTimer();
  }

  setTimeout() {
    let timeout = parseInt((window as any).app.props.loginTimeout);

    if (timeout < 1) {
      this.timeout = 10;
    }

    this.timeout = timeout;
  }

  startTimer() {
    window.setTimeout(function () {
      SignOut.showWidget();
    }, (this.timeout - 1) * 60 * 1000);
  }

  static showWidget() {
    const modalElement = document.getElementById("signout__modal-modal");
    console.log("The session expires. You will be logged out in a minute.");

    if (modalElement) {
      let modal = new Bootstrap.Modal(modalElement, {
        backdrop: "static",
        keyboard: false,
        focus: true,
      });

      modal.show();
      SignOut.startCountdown();

      document
        .querySelector(".signout__modal-button")
        .addEventListener("click", function () {
          location.reload();
        });
    }
  }

  static startCountdown() {
    const TIMER_ELEMENT = document.querySelector(".signout__modal-timer");

    let count = 59,
      timer = setInterval(function () {
        TIMER_ELEMENT.innerHTML = "00:" + count--;
        if (count == 0) {
          clearInterval(timer);
          window.location.href = (window as any).app.props.baseUrl + "signout";
        }
      }, 1000);
  }
}
