/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IImportResponse } from './interfaces/IImportResponse';

export class ImportResponse implements IImportResponse {
  public systemId: number;

  public success: boolean;

  public message: string;

  public constructor(
    systemId: number = 0,
    success: boolean = false,
    message: string = '',
  ) {
    this.systemId = systemId;
    this.success = success;
    this.message = message;
  }
}
