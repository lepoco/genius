/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */
import { IExpertCondition } from './IExpertCondition';
import { IExpertProduct } from './IExpertProduct';
import { IExpertRelation } from './IExpertRelation';

/**
 * Represents the expert system.
 */
export interface IExpertSystem {
  id: number;
  guid: string;
  version: string;
  name: string;
  description: string;
  author: string;
  source: string;
  type: string;
  confidence: number;
  question: string;
  createdAt: string;
  updatedAt: string;
  products: IExpertProduct[];
  conditions: IExpertCondition[];
  relations: IExpertRelation[];
  productsCount: number;
  conditionsCount: number;
  relationsCount: number;
}
