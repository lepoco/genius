/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertCondition } from './interfaces/IExpertCondition';
import { ISolverResultingProduct } from './interfaces/ISolverResultingProduct';

/**
 * Represents an expert system product.
 */
export class SolverResultingProduct implements ISolverResultingProduct {
  public id: number;

  public systemId: number;

  public name: string;

  public description: string;

  public notes: string;

  public confirming: IExpertCondition[];

  public negating: IExpertCondition[];

  public indifferent: IExpertCondition[];

  public constructor(
    id: number = 0,
    system_id: number = 0,
    name: string = '',
    description: string = '',
    notes: string = '',
    confirming: IExpertCondition[] = [],
    negating: IExpertCondition[] = [],
    indifferent: IExpertCondition[] = [],
  ) {
    this.id = id;
    this.systemId = system_id;
    this.name = name;
    this.description = description;
    this.notes = notes;

    this.confirming = confirming;
    this.negating = negating;
    this.indifferent = indifferent;
  }
}
