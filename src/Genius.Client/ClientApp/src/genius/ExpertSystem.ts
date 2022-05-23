/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertAbout } from './interfaces/IExpertAbout';
import { IExpertCondition } from './interfaces/IExpertCondition';
import { IExpertProduct } from './interfaces/IExpertProduct';
import { IExpertRelation } from './interfaces/IExpertRelation';
import { IExpertSystem } from './interfaces/IExpertSystem';

/**
 * Represents the expert system.
 */
export class ExpertSystem implements IExpertSystem {
  public id: number;
  public guid: string;
  public version: string;
  public name: string;
  public description: string;
  public type: string;
  public question: string;
  public createdAt: string;
  public updatedAt: string;

  public conditions: IExpertCondition[];
  public products: IExpertProduct[];
  public relations: IExpertRelation[];

  public productsCount: number;
  public conditionsCount: number;
  public relationsCount: number;

  public author: string;
  public source: string;
  public confidence: number;

  public constructor(
    systemId: number = 0,
    systemGuid: string = '',
    systemVersion: string = '1.0.0',
    systemName: string = '',
    systemDescription: string = '',
    author: string = '',
    source: string = '',
    confidence: number = 0,
    systemType: string = '',
    systemQuestion: string = '',
    systemCreatedAt: string = '',
    systemUpdatedAt: string = '',
    systemConditions: IExpertCondition[] = [],
    systemProducts: IExpertProduct[] = [],
    systemRelations: IExpertRelation[] = [],
    
  ) {
    this.id = systemId;
    this.guid = systemGuid;
    this.version = systemVersion;
    this.name = systemName;
    this.description = systemDescription;
    this.author = author;
    this.source = source;
    this.confidence = confidence;
    this.type = systemType;
    this.question = systemQuestion;
    this.createdAt = systemCreatedAt;
    this.updatedAt = systemUpdatedAt;
    this.conditions = systemConditions;
    this.products = systemProducts;
    this.relations = systemRelations;

    this.productsCount = systemProducts.length;
    this.conditionsCount = systemConditions.length;
    this.relationsCount = systemRelations.length;
  }

  public setConditions(conditions: IExpertCondition[]): void {
    this.conditions = conditions;
    this.conditionsCount = conditions.length;
  }

  public setProducts(products: IExpertProduct[]): void {
    this.products = products;
    this.productsCount = products.length;
  }

  public setRelations(relations: IExpertRelation[]): void {
    this.relations = relations;
    this.relationsCount = relations.length;
  }

  public updateAbout(about: IExpertAbout): void {
    if (about.id !== this.id) return;

    this.productsCount = about.products;
    this.conditionsCount = about.conditions;
    this.relationsCount = about.relations;
  }
}
