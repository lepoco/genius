import AppData from "./appdata";

export const name = "SignOut";

/**
 * Displays information about the ending time of the session.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Common/SignOut
 * @license GPL-3.0
 * @since   1.1.0
 */
export default class SignOut {
  timeout: number = 0;

  static init() {
    return new SignOut();
  }

  constructor() {
    if (AppData.isDebug()) {
      console.debug("App\\Common\\SignOut REGISTERED", AppData.isLogged());
    }

    if (!AppData.isLogged()) {
      return;
    }

    this.setTimeout();
    this.startTimer();
  }

  setTimeout() {
    let timeout = AppData.signoutTime();

    if (AppData.isDebug()) {
      console.debug("App\\Common\\SignOut TIMEOUT STARTED", timeout);
    }

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
    const modalElement = document.querySelector(".signout");

    if (AppData.isDebug()) {
      console.debug("App\\Common\\SignOut TIME PASSED; MINUTE LEFT");
    }

    if (modalElement) {
      modalElement.classList.add("--show");

      SignOut.startCountdown();
    }
  }

  static startCountdown() {
    const TIMER_ELEMENT = document.querySelector(".signout__modal--timer");

    let count = 59,
      timer = setInterval(function () {
        TIMER_ELEMENT.innerHTML = "00:" + count--;

        if (count == 0) {
          clearInterval(timer);
          window.location.href = AppData.url("signout");
        }
      }, 1000);
  }
}
