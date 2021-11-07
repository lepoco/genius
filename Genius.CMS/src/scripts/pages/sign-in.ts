import AppData from "./../common/appdata";
import FormRequest from "../common/formrequest";

/**
 * Page controller for sign-in.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Pages/SignIn
 * @license GPL-3.0
 * @since   1.1.0
 */
FormRequest.register("#signin", function (status: string, response: any) {
  if (response.status === "S01") {
    window.location.href = AppData.url();
  }
});
