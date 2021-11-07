import FormRequest from "./../common/formrequest";
import Toast from "./../common/toast";

/**
 * Page controller for dashboard.account.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Pages/Dashboard/Account
 * @license GPL-3.0
 * @since   1.1.0
 */
FormRequest.register("#account", function (status: String, response: any) {
  if ("S01" === response.status) {
    const PICTURE_ELEMENT = document.querySelector('input[name="picture"]');

    if (PICTURE_ELEMENT) {
      (PICTURE_ELEMENT as HTMLInputElement).value = "";
    }

    const pictureContainer = document.querySelector(
      ".account__banner__picture"
    );
    const pictureImage = document.querySelector(
      ".profile_picture"
    ) as HTMLObjectElement;
    const picturePlaceholder = document.querySelector(".profile_placeholder");

    if (pictureImage && response.content.hasOwnProperty("picture")) {
      pictureImage.data = response.content.picture;
    }

    if (picturePlaceholder && response.content.hasOwnProperty("picture")) {
      picturePlaceholder.remove();

      let picture = document.createElement("img");
      picture.src = response.content.picture;
      pictureContainer.appendChild(picture);
    }
  }
});

const PROFILE_PICTURE = document.querySelector('.editable__picture') as HTMLImageElement;

if (PROFILE_PICTURE) {
  PROFILE_PICTURE.onerror = function () {
    PROFILE_PICTURE.src = PROFILE_PICTURE.dataset.errorscr;
  };
}
