import { json } from 'stream/consumers';

export enum RestStatus {
  Unknown,
  Success,
  Failed,
}

export class RestResponse {
  public status: RestStatus;
  public isError: boolean;
  public timestamp: number;
  public message: string;
  public errorMessage: string;
  public result: any;

  public constructor() {
    this.status = RestStatus.Unknown;
    this.isError = true;
    this.timestamp = 0;
    this.errorMessage = '';
    this.message = '';
    this.result = null;
  }

  public static fetch(jsonData: any): RestResponse {
    console.debug('\\RestResponse\\fetch\\jsonData', jsonData);

    const response = new RestResponse();

    response.isError = jsonData?.isError ?? true;
    response.status = RestResponse.numberToStatus(jsonData?.status ?? 0);
    response.timestamp = jsonData?.timestmap ?? new Date().getTime();
    response.message = jsonData?.message ?? '';
    response.errorMessage = jsonData?.errorMessage ?? 'Unable to fetch REST response';
    response.result = jsonData?.result ?? null;

    return response;
  }

  private static numberToStatus(rawStatus: any): RestStatus {
    const statusNumber = parseInt(rawStatus ?? 0);

    switch (statusNumber) {
      case 1:
        return RestStatus.Success;
      case 2:
        return RestStatus.Failed;
      default:
        return RestStatus.Unknown;
    }
  }
}
