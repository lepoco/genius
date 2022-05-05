/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertRelation } from './interfaces/IExpertRelation';

/**
 * Represents an expert system relation between Product and Condition.
 */
export class ExpertRelation implements IExpertRelation {
  public id: number;

  public systemId: number;

  public conditionId: number;

  public productId: number;

  public weight: number;

  public constructor(
    id: number = 0,
    systemId: number = 0,
    conditionId: number = 0,
    productId: number = 0,
    weight: number = 100,
  ) {
    this.id = id;
    this.systemId = systemId;
    this.conditionId = conditionId;
    this.productId = productId;
    this.weight = weight;
  }
}
