import Request from "./../common/request";
import Toast from "./../common/toast";

Request.register("#account", function (status: String, response: any) {
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
