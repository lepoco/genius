import Request from "./../common/request";
import Toast from "./../common/toast";

Request.register("#createNew", function (status: String, response: any) {
  if ("S01" === response.status) {
    console.log(response);
  }
});
