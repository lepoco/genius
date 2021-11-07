import AppData from "./../common/appdata";
import FormRequest from "../common/formrequest";
import Toast from "./../common/toast";

/**
 * Page controller for installer.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Pages/Installer
 * @license GPL-3.0
 * @since   1.1.0
 */
FormRequest.register("#install", function (status: string, response: any) {
  console.log(response);

  switch (response.status) {
    case "S01":
      Toast.send(
        "It worked!",
        "The application has been successfully installed. The page will be refreshed in a moment...",
        "success"
      );
      window.setTimeout(function () {
        window.location.href = AppData.url();
      }, 500);
      break;
  }
});
