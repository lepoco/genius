import Request from "./../common/request";

Request.register("#changePassword", function (status: string, response: any) {
  if ("S01" === response.status) {
    let fields = document.querySelectorAll('input[type="password"]');
    fields.forEach(function (field: HTMLFormElement) {
      field.value = "";
    });
  }
});
