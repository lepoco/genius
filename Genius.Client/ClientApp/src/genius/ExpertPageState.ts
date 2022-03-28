/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertPageState from './interfaces/IExpertPageState';
import IExpertCondition from './interfaces/IExpertCondition';
import IExpertRelation from './interfaces/IExpertRelation';
import IExpertProduct from './interfaces/IExpertProduct';

export default class ExpertPageState implements IExpertPageState {
  public systemLoaded: boolean = false;
  public id: number = 0;
  public guid: string = '';
  public version: string = '';
  public name: string = '';
  public description: string = '';
  public type: string = '';
  public question: string = '';
  public createdAt: string = '';
  public updatedAt: string = '';
  public conditions: IExpertCondition[] = [];
  public products: IExpertProduct[] = [];
  public relations: IExpertRelation[] = [];
  public productsCount: number = 0;
  public conditionsCount: number = 0;
  public relationsCount: number = 0;
}
