import Request from "./../common/request";
import Toast from "./../common/toast";

Request.register("#install", function (status: string, response: any) {
  console.log(response);

  switch (response.status) {
    case "S01":
      Toast.send(
        "It worked!",
        "The application has been successfully installed. The page will be refreshed in a moment...",
        "success"
      );
      window.setTimeout(function () {
        window.location.href = (window as any).app.props.baseUrl;
      }, 500);
      break;
  }
});
