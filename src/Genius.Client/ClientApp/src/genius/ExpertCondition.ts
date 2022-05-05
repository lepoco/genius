/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertCondition } from './interfaces/IExpertCondition';

/**
 * Represents an expert system condition.
 */
export class ExpertCondition implements IExpertCondition {
  public id: number = 0;

  public systemId: number = 0;

  public name: string = '';

  public description: string = '';

  public constructor(
    id: number = 0,
    systemId: number = 0,
    name: string = '',
    description: string = '',
  ) {
    this.id = id;
    this.systemId = systemId;
    this.name = name;
    this.description = description;
  }
}
