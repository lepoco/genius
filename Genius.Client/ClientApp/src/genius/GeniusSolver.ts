/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { GeniusApi } from './GeniusApi';
import { GeniusDataParser } from './GeniusDataParser';

import { IExpertSystem } from './interfaces/IExpertSystem';
import { IExpertCondition } from './interfaces/IExpertCondition';
import { ISolverResultingProduct } from './interfaces/ISolverResultingProduct';
import { ISolverQuestion } from './interfaces/ISolverQuestion';
import { ISolverResponse } from './interfaces/ISolverResponse';
import { SolverResponse } from './SolverResponse';
import { SolverResultingProduct } from './SolverResultingProduct';
import { ExpertSystem } from './ExpertSystem';
import { IExpertProduct } from './Genius';
import { ExpertCondition } from './ExpertCondition';
import { ExpertProduct } from './ExpertProduct';

/**
 * Contains methods for solving the expert system.
 */
export class GeniusSolver {
  private static readonly BASE_SOLVER_GATEWAY: string = '/api/solver/';

  private selectedSystem: IExpertSystem;

  public constructor() {
    this.selectedSystem = new ExpertSystem();
  }

  public setSystem(system: IExpertSystem): void {
    this.selectedSystem = system;
  }

  public async populateSystemById(systemId: number): Promise<boolean> {
    this.selectedSystem = await GeniusApi.getSystemById(systemId, true, true, true);

    return true;
  }

  public async populateSystemByGuid(systemGuid: string): Promise<boolean> {
    this.selectedSystem = await GeniusApi.getSystemByGuid(systemGuid, true, true, true);

    return true;
  }

  /**
   * Asks a question to the service solving expert systems.
   * @param question Terms of the question
   * @returns Response from solver.
   */
  public async ask(question: ISolverQuestion): Promise<ISolverResponse> {
    const confirmingIds: number[] = (question.confirming ?? []).map(({ id }) => id ?? 0);
    const negatingIds: number[] = (question.negating ?? []).map(({ id }) => id ?? 0);
    const indifferentIds: number[] = (question.indifferent ?? []).map(
      ({ id }) => id ?? 0,
    );

    const formData = GeniusDataParser.buildFormData({
      systemId: question.systemId ?? 0,
      multiple: question.multiple ?? false,
      confirming: confirmingIds,
      negating: negatingIds,
      indifferent: indifferentIds,
    });

    let response = await fetch(GeniusSolver.BASE_SOLVER_GATEWAY + 'ask', {
      method: 'POST',
      body: formData,
    });

    const responseData = await response.json();

    // console.debug('\\GeniusSolver\\ask\\responseData', responseData);

    return await this.fetchResponseObject(responseData);
  }

  private async getCondition(conditionId: number): Promise<IExpertCondition> {
    const savedConditions = this.selectedSystem.conditions.filter(function (con) {
      return con.id === conditionId;
    });

    if (savedConditions.length > 0) {
      // console.debug('\\GeniusSolver\\getCondition\\fromCache', true);
      return savedConditions[0];
    } else {
      // console.debug('\\GeniusSolver\\getCondition\\fromCache', false);
      return conditionId > 0
        ? await GeniusApi.getCondition(conditionId)
        : new ExpertCondition();
    }
  }

  private async getManyConditions(conditionsIds: number[]): Promise<IExpertCondition[]> {
    let conditions: IExpertCondition[] = [];

    for (const singleId of conditionsIds) {
      conditions.push(await this.getCondition(singleId));
    }

    return conditions;
  }

  private async getProduct(productId: number): Promise<IExpertProduct> {
    const savedProducts = this.selectedSystem.products.filter(function (prod) {
      return prod.id === productId;
    });

    if (savedProducts.length > 0) {
      // console.debug('\\GeniusSolver\\getProduct\\fromCache', true);
      return savedProducts[0];
    } else {
      // console.debug('\\GeniusSolver\\getProduct\\fromCache', false);
      return productId > 0 ? await GeniusApi.getProduct(productId) : new ExpertProduct();
    }
  }

  private async getManyProducts(productsIds: number[]): Promise<IExpertProduct[]> {
    let products: IExpertProduct[] = [];

    for (const singleId of productsIds) {
      products.push(await this.getProduct(singleId));
    }

    return products;
  }

  private async getConditionsFromRelations(
    relationsIds: number[],
  ): Promise<IExpertCondition[]> {
    let conditions: IExpertCondition[] = [];

    for (const singleRelationId of relationsIds) {
      if (singleRelationId < 1) {
        continue;
      }

      const savedRelations = this.selectedSystem.relations.filter(function (rel) {
        return rel.id === singleRelationId;
      });

      if (savedRelations.length > 0) {
        conditions.push(await this.getCondition(savedRelations[0].conditionId));
      } else {
        const dbRelation = await GeniusApi.getRelation(singleRelationId);

        if (dbRelation.id > 0) {
          conditions.push(await this.getCondition(dbRelation.conditionId));
        }
      }
    }

    return conditions;
  }

  private async fetchResultingProductsObjects(
    productObjects: any[],
  ): Promise<ISolverResultingProduct[]> {
    const products = await this.getManyProducts(productObjects);
    let resultingProducts: ISolverResultingProduct[] = [];

    for (const singleProduct of products) {
      const singleProductRelations = await GeniusApi.getProductRelations(
        singleProduct.id,
      );

      resultingProducts.push(
        new SolverResultingProduct(
          singleProduct.id,
          singleProduct.systemId,
          singleProduct.name,
          singleProduct.description,
          singleProduct.notes,
          await this.getConditionsFromRelations(singleProductRelations.confirming),
          await this.getConditionsFromRelations(singleProductRelations.negating),
          await this.getConditionsFromRelations(singleProductRelations.indifferent),
        ),
      );
    }

    return resultingProducts;
  }

  private async fetchResponseObject(dataObject: any): Promise<ISolverResponse> {
    const products = await this.fetchResultingProductsObjects(
      Array.isArray(dataObject.products) ? dataObject.products : [],
    );

    const nextCondition = await this.getCondition(dataObject.nextCondition ?? 0);

    // TODO: Fix solving
    return new SolverResponse(
      dataObject.systemId ?? 0,
      dataObject.isSolved ?? false,
      dataObject.status ?? 0,
      products,
      nextCondition,
    );
  }
}
