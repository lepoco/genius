/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */
import ISolverResponse from './interfaces/ISolverResponse';
import IExpertCondition from './interfaces/IExpertCondition';
import IExpertProduct from './interfaces/IExpertProduct';

export default class SolverResponse implements ISolverResponse {
  public systemId: number = 0;

  public isSolved: boolean = false;

  public status: number = 0;

  public nextCondition?: IExpertCondition;

  public products: IExpertProduct[] = [];

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
