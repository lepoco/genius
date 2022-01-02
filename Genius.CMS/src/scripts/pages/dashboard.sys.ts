import FormRequest from "./../common/formrequest";

/**
 * Page controller for dashboard.add.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Pages/Dashboard/Add
 * @license GPL-3.0
 * @since   2.0.0
 */
FormRequest.register("#answer", function (status: String, response: any) {
  if (
    response.hasOwnProperty("content") &&
    response.content.hasOwnProperty("sessionData")
  ) {
    var element = document.querySelector('input[name="system_session"]');

    if (element instanceof HTMLInputElement) {
      element.value = response.content.sessionData;
    }
  }

  console.debug(response);
});
