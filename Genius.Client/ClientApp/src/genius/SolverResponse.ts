/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */
import ISolverResponse from './ISolverResponse';
import IExpertCondition from './IExpertCondition';
import IExpertProduct from './IExpertProduct';

export default class SolverResponse implements ISolverResponse {
  systemId: number = 0;

  isSolved: boolean = false;

  status: number = 0;

  nextCondition?: IExpertCondition;

  products: IExpertProduct[] = [];

  public constructor(
    systemId: number,
    isSolved: boolean,
    status: number,
    products: IExpertProduct[],
    nextCondition: IExpertCondition | null = null,
  ) {
    this.systemId = systemId;
    this.isSolved = isSolved;
    this.status = status;
    this.products = products;

    if (nextCondition !== null) {
      this.nextCondition = nextCondition;
    }
  }
}
