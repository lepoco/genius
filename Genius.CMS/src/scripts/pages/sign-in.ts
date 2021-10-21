import Request from "./../common/request";

Request.register("#signin", function (status:string, response:any) {
  if (response.status === "S01") {
    window.location.href =
    (window as any).app.props.baseUrl + (window as any).app.props.dashboard;
  }
});
