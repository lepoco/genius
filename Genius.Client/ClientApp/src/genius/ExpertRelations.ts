/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertRelations from './interfaces/IExpertRelations';

/**
 * Represents relations and their type of belonging to a given element.
 */
export default class ExpertRelations implements IExpertRelations {
  public id: number = 0;

  public systemId: number = 0;

  public confirming: number[];

  public negating: number[];

  public indifferent: number[];

  public constructor(
    id: number = 0,
    system_id: number = 0,
    confirming: number[] = [],
    negating: number[] = [],
    indifferent: number[] = [],
  ) {
    this.id = id;
    this.systemId = system_id;
    this.confirming = confirming;
    this.negating = negating;
    this.indifferent = indifferent;
  }

  public getAll(): number[] {
    return this.confirming.concat(this.negating).concat(this.indifferent);
  }

  public count(): number {
    return this.getAll().length;
  }
}
