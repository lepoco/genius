import AppData from "./../common/appdata";
import FormRequest from "../common/formrequest";
import Toast from "./../common/toast";

/**
 * Page controller for register.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Pages/Register
 * @license GPL-3.0
 * @since   1.1.0
 */
FormRequest.register("#register", function (status: string, response: any) {
  if ("S01" === response.status) {
    window.location.href = AppData.url("signin");
  }
});
