/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */
import { ISolverResponse } from './interfaces/ISolverResponse';
import { IExpertCondition } from './interfaces/IExpertCondition';
import { ISolverResultingProduct } from './interfaces/ISolverResultingProduct';
import { ExpertCondition } from './ExpertCondition';

export class SolverResponse implements ISolverResponse {
  public systemId: number = 0;

  public isSolved: boolean = false;

  public status: number = 0;

  public nextCondition: IExpertCondition;

  public products: ISolverResultingProduct[] = [];

  public constructor(
    systemId: number = 0,
    isSolved: boolean = false,
    status: number = 0,
    products: ISolverResultingProduct[] = [],
    nextCondition: IExpertCondition | null = null,
  ) {
    this.systemId = systemId;
    this.isSolved = isSolved;
    this.status = status;
    this.products = products;

    this.nextCondition = nextCondition ?? new ExpertCondition();
  }
}
