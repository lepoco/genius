import Request from "./../common/request";
import Toast from "./../common/toast";

Request.register("#register", function (status: string, response: any) {
  if ("S01" === response.status) {
    window.location.href = (window as any).app.props.baseUrl + "signin";
  }
});
