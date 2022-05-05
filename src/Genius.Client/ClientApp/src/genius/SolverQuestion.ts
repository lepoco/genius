/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */
import { ISolverQuestion } from './interfaces/ISolverQuestion';
import { IExpertCondition } from './interfaces/IExpertCondition';

export class SolverQuestion implements ISolverQuestion {
  public systemId: number;

  public multiple: boolean;

  public confirming: IExpertCondition[];

  public negating: IExpertCondition[];

  public indifferent: IExpertCondition[];

  public constructor(
    systemId: number = 0,
    multiple: boolean = false,
    confirming: IExpertCondition[] = [],
    negating: IExpertCondition[] = [],
    indifferent: IExpertCondition[] = [],
  ) {
    this.systemId = systemId;
    this.multiple = multiple;
    this.confirming = confirming;
    this.negating = negating;
    this.indifferent = indifferent;
  }
}
