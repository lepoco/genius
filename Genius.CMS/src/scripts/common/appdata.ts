export const name = "AppData";

/**
 * Class retrieves the data stored in the window.app and then facilitates its use.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Common/AppData
 * @license GPL-3.0
 * @since   1.1.0
 */
export default class AppData {
  /**
   * @return Sets current routing status.
   */
  static setRouting(params: object): void {
    AppData.container().routing = params;
  }

  /**
   * @return Adds a notification to the list.
   */
  static addToast(title: string, message: string, date:any): void {
    if (!AppData.container().hasOwnProperty("toasts")) {
      AppData.container().toasts = [];
    }

    AppData.container().toasts.push([title, message, date]);
  }

  /**
   * @return Base application url.
   */
  static url(path: string = ""): string {
    const BASE = AppData.container().props.baseUrl;

    return BASE + path;
  }

  /**
   * @return Time in which session expires.
   */
  static signoutTime(): number {
    return parseInt(AppData.container().props.signoutTime);
  }

  /**
   * @return Gets currently rendered page.
   */
  static pageNow(): string {
    return AppData.container().props.view;
  }

  /**
   * @return Gets information whether the Service Worker is enabled.
   */
   static isWorkerEnabled(): boolean {
    return true == AppData.container().props.serviceWorkerEnabled;
  }

  /**
   * @return Gets information whether the user is logged in.
   */
  static isLogged(): boolean {
    return true === AppData.container().auth.loggedIn;
  }

  /**
   * @return Gets information whether the user has internet connection.
   */
  static isOnline(): boolean {
    return window.navigator.onLine;
  }

  /**
   * @return Gets information whether the debugging is enabled.
   */
  static isDebug(): boolean {
    return AppData.container().props.debug;
  }

  /**
   * @return Gets information whether the connection is established using SSL.
   */
  static isSecured(): boolean {
    return AppData.container().props.secured;
  }

  /**
   * @return Gets url of ajax gateway.
   */
  static gateway(): string {
    return AppData.container().props.ajax;
  }

  /**
   * @return Gets the name of the default cookie.
   */
  static cookieName(): string {
    return AppData.container().props.cookieName;
  }

  /**
   * @return Prints current app data.
   */
  static dump(): void {
    console.debug("App\\Common\\AppData IS_ONLINE", AppData.isOnline());
    console.debug("App\\Common\\AppData", AppData.container());
  }

  private static container(): any {
    return (window as any).app;
  }
}
