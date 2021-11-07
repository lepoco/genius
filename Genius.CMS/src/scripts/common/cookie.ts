import AppData from "./appdata";
import * as Cookies from "./../components/js.cookie";

export const name = "Cookie";

/**
 * Displays information about cookies.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Common/Cookie
 * @license GPL-3.0
 * @since   1.1.0
 */
export default class Cookie {
  cookieName: string = "access_cookie";

  container: any;
  buttons: HTMLCollectionOf<HTMLButtonElement>;

  static init(): Cookie {
    return new Cookie();
  }

  constructor() {
    this.cookieName = AppData.cookieName();

    this.registerElements();
    this.checkCookie();
  }

  actionAccept(): void {
    // TODO: Fix cookies
    this.setCookie();

    if (this.container != undefined && this.container.length > 0) {
      this.container[0].style.display = "none";
    }
  }

  actionManage(): void {
    //TODO: Manage cookies
    console.log("Manage Cookies");
  }

  checkCookie(): void {
    let cookie = Cookies.get(this.cookieName);

    if (cookie == undefined) {
      if (this.container != undefined && this.container.length > 0) {
        this.container[0].style.display = "block";
      }
    }
  }

  registerElements(): void {
    this.container = document.getElementsByClassName("cookie");
    this.buttons = document.getElementsByClassName(
      "cookie__button"
    ) as HTMLCollectionOf<HTMLButtonElement>;

    if (this.buttons != undefined && this.buttons.length > 0) {
      this.loopButtons();
    }
  }

  loopButtons(): void {
    for (let index = 0; index < this.buttons.length; index++) {
      if (this.buttons[index].classList.contains("--manage")) {
        this.buttons[index].addEventListener("click", (e) => {
          e.preventDefault();
          this.actionAccept();
        });
      } else if (this.buttons[index].classList.contains("--accept")) {
        this.buttons[index].addEventListener("click", (e) => {
          e.preventDefault();
          this.actionAccept();
        });
      }
    }
  }

  setCookie(): void {
    //TODO: Set acceptance cookie
    Cookies.set(this.cookieName, "{0,0,0,0}", {
      expires: 365,
      path: "/",
      secure: AppData.isSecured(),
      sameSite: "Lax",
    });
  }
}
