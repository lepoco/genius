/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IImportRequest } from './interfaces/IImportRequest';

export class ImportRequest implements IImportRequest {
  public systemId: number;

  public file: File;

  public systemName: string;

  public systemDescription: string;

  public systemQuestion: string;

  public systemAuthor: string;

  public systemSource: string;

  public systemType: string;

  public systemConfidence: number;

  public constructor(
    systemId: number,
    file: File,
    systemName: string = '',
    systemDescription: string = '',
    systemQuestion: string = '',
    systemAuthor: string = '',
    systemSource: string = '',
    systemType: string = '',
    systemConfidence: number = 0,
  ) {
    this.systemId = systemId;
    this.file = file;
    this.systemName = systemName;
    this.systemDescription = systemDescription;
    this.systemQuestion = systemQuestion;
    this.systemAuthor = systemAuthor;
    this.systemSource = systemSource;
    this.systemType = systemType;
    this.systemConfidence = systemConfidence;
  }
}
