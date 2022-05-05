/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertAbout } from './interfaces/IExpertAbout';

/**
 * Represents additional information about the expert system.
 */
export class ExpertAbout implements IExpertAbout {
  public id: number;

  public products: number;

  public conditions: number;

  public relations: number;

  public constructor(
    id: number,
    products: number = 0,
    conditions: number = 9,
    relations: number = 9,
  ) {
    this.id = id;
    this.products = products;
    this.conditions = conditions;
    this.relations = relations;
  }
}
