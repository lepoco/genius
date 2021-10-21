import * as Cookies from "./../components/js.cookie";

export const name = "Cookie";

export default class Cookie {
  cookieName: string = "pkx_cookie";

  container: any;
  buttons: HTMLCollectionOf<HTMLButtonElement>;

  constructor() {
    this.registerElements();
    this.checkCookie();
  }

  actionAccept() {
    // TODO: Fix cookies
    this.setCookie();

    if (this.container != undefined && this.container.length > 0) {
      this.container[0].style.display = "none";
    }
  }

  actionManage() {
    //TODO: Manage cookies
    console.log("Manage Cookies");
  }

  checkCookie() {
    let cookie = Cookies.get(this.cookieName);

    if (cookie == undefined) {
      if (this.container != undefined && this.container.length > 0) {
        this.container[0].style.display = "block";
      }
    }
  }

  registerElements() {
    this.container = document.getElementsByClassName("cookie");
    this.buttons = document.getElementsByClassName(
      "cookie__button"
    ) as HTMLCollectionOf<HTMLButtonElement>;

    if (this.buttons != undefined && this.buttons.length > 0) {
      this.loopButtons();
    }
  }

  loopButtons() {
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

  setCookie() {
    //TODO: Set acceptance cookie
    Cookies.set(this.cookieName, "{0,0,0,0}", {
      expires: 365,
      path: "/",
      secure: (window as any).app.props.secured,
      sameSite: "Lax",
    });
  }
}
