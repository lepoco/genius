/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertCondition from './IExpertCondition';
import IExpertProduct from './IExpertProduct';

import IExpertSystem from './IExpertSystem';

export default class ExpertSystem implements IExpertSystem {
  systemId: number = 0;
  systemGuid: string = '';
  systemVersion: string = '';
  systemName: string = '';
  systemDescription: string = '';
  systemType: string = '';
  systemQuestion: string = '';
  systemCreatedAt: string = '';
  systemUpdatedAt: string = '';

  systemConditions: IExpertCondition[] = [];
  systemProducts: IExpertProduct[] = [];

  constructor(
    systemId: number = 0,
    systemGuid: string = '',
    systemVersion: string = '1.0.0',
    systemName: string = '',
    systemDescription: string = '',
    systemType: string = '',
    systemQuestion: string = '',
    systemCreatedAt: string = '',
    systemUpdatedAt: string = '',
    systemConditions: IExpertCondition[] = [],
    systemProducts: IExpertProduct[] = [],
  ) {
    this.systemId = systemId;
    this.systemGuid = systemGuid;
    this.systemVersion = systemVersion;
    this.systemName = systemName;
    this.systemDescription = systemDescription;
    this.systemType = systemType;
    this.systemQuestion = systemQuestion;
    this.systemCreatedAt = systemCreatedAt;
    this.systemUpdatedAt = systemUpdatedAt;
    this.systemConditions = systemConditions;
    this.systemProducts = systemProducts;
  }

  setConditions(conditions: IExpertCondition[]) {
    this.systemConditions = conditions;
  }

  setProducts(products: IExpertProduct[]) {
    this.systemProducts = products;
  }
}
