/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { GeniusDataParser } from './GeniusDataParser';

import { IExpertSystem } from './interfaces/IExpertSystem';
import { IExpertCondition } from './interfaces/IExpertCondition';
import { IExpertProduct } from './interfaces/IExpertProduct';
import { ExpertSystem } from './ExpertSystem';
import { IExpertRelation } from './interfaces/IExpertRelation';
import { IImportResponse } from './interfaces/IImportResponse';
import { IImportRequest } from './interfaces/IImportRequest';
import { ImportResponse } from './ImportResponse';
import { ExpertRelations } from './ExpertRelations';
import { ExpertAbout } from './ExpertAbout';

/**
 * Contains logic responsible for polling the internal API that connects via gRPC to the Genius microservice.
 */
export class GeniusApi {
  private static readonly BASE_EXPERT_GATEWAY: string = '/api/expert/';

  //#region SYSTEM ACTIONS

  /**
   * Adds a new expert system to the database.
   * @param system New system to be added.
   * @returns true if the operation was successful.
   */
  public static async addSystem(system: IExpertSystem): Promise<boolean> {
    if (system.name === '' || system.type === '') {
      console.debug('\\GeniusApi\\addSystem', false);

      return false;
    }

    let formData = GeniusDataParser.buildFormData({
      name: system.name ?? '',
      description: system.description ?? '',
      question: system.question ?? '',
      type: system.type ?? '',
    });

    let response = await fetch('api/expert/system', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addSystem\\responseText', responseText);

    return responseText === 'success';
  }

  public static async getSystemById(
    systemId: number,
    fetchProducts: boolean = false,
    fetchConditions: boolean = false,
    fetchRelations: boolean = false,
  ): Promise<IExpertSystem> {
    const response = await fetch(GeniusApi.BASE_EXPERT_GATEWAY + 'system/' + systemId);
    const data = await response.json();

    // console.debug('\\GeniusApi\\getSystemByGuid\\data', data);

    return await GeniusApi.fetchSystemObject(
      data,
      fetchProducts,
      fetchConditions,
      fetchRelations,
    );
  }

  /**
   * Get the system by its GUID.
   * @param guid Universal identifier of system in the database.
   * @param fetchProducts Whether the call should also fetch the system products.
   * @param fetchConditions Whether the call should also fetch the system conditions.
   * @param fetchRelations Whether the call should also fetch the system relations.
   * @returns Instance of expert system.
   */
  public static async getSystemByGuid(
    guid: string,
    fetchProducts: boolean = false,
    fetchConditions: boolean = false,
    fetchRelations: boolean = false,
  ): Promise<IExpertSystem> {
    const response = await fetch(GeniusApi.BASE_EXPERT_GATEWAY + 'system/guid/' + guid);
    const data = await response.json();

    console.debug('\\GeniusApi\\getSystemByGuid\\data', data);

    return await GeniusApi.fetchSystemObject(
      data,
      fetchProducts,
      fetchConditions,
      fetchRelations,
    );
  }

  /**
   * Tries to remove the expert system with the given ID.
   * @param id Expert system identifier.
   * @returns true if the operation was successful.
   */
  public static async deleteSystem(id: number): Promise<boolean> {
    const response = await fetch(GeniusApi.BASE_EXPERT_GATEWAY + 'system/' + id, {
      method: 'DELETE',
    });

    console.debug('\\GeniusApi\\deleteSystem\\id', id);

    const responseText = await response.text();

    console.debug('\\GeniusApi\\deleteSystem\\responseText', responseText);

    return responseText === 'success';
  }

  public static async updateSystem(system: IExpertProduct): Promise<boolean> {
    //TODO: TO DO
    return false;
  }

  public static async getSystemAbout(systemId: number): Promise<ExpertAbout> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'system/' + systemId + '/about',
    );
    const data = await response.json();

    console.debug('\\GeniusApi\\getSystemAbout\\data', data);

    return GeniusDataParser.fetchSystemAboutObject(data);
  }

  /**
   * Retrieves the products assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of products assigned to the system.
   */
  public static async getSystemProducts(id: number): Promise<IExpertProduct[]> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'system/' + id + '/products',
    );
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let productsList: IExpertProduct[] = [];

    for (let key in data) {
      productsList.push(GeniusDataParser.fetchProductObject(data[key]));
    }

    // console.debug('\\GeniusApi\\getSystemProducts\\productsList', productsList);

    return productsList;
  }

  /**
   * Retrieves the conditions assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of conditions assigned to the system.
   */
  public static async getSystemConditions(id: number): Promise<IExpertCondition[]> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'system/' + id + '/conditions',
    );
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let conditionsList: IExpertCondition[] = [];

    for (let key in data) {
      conditionsList.push(GeniusDataParser.fetchConditionObject(data[key]));
    }

    // console.debug('\\GeniusApi\\getSystemConditions\\conditionsList', conditionsList);

    return conditionsList;
  }

  /**
   * Retrieves the relations assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of relations assigned to the system.
   */
  public static async getSystemRelations(id: number): Promise<IExpertRelation[]> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'system/' + id + '/relations',
    );
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let relationsList: IExpertRelation[] = [];

    for (let key in data) {
      relationsList.push(GeniusDataParser.fetchRelationObject(data[key]));
    }

    // console.debug('\\GeniusApi\\getSystemRelations\\relationsList', relationsList);

    return relationsList;
  }

  /**
   * Retrieves all known expert systems from the database.
   * @param fetchProducts Whether the call should also fetch the system products.
   * @param fetchConditions Whether the call should also fetch the system conditions.
   * @param fetchRelations Whether the call should also fetch the system relations.
   * @returns
   */
  public static async getAllSystems(
    fetchProducts: boolean = false,
    fetchConditions: boolean = false,
    fetchRelations: boolean = false,
  ): Promise<IExpertSystem[]> {
    const response = await fetch(GeniusApi.BASE_EXPERT_GATEWAY + 'system');
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let systemsList: IExpertSystem[] = [];

    for (let key in data) {
      systemsList.push(
        await GeniusApi.fetchSystemObject(
          data[key],
          fetchProducts,
          fetchConditions,
          fetchRelations,
        ),
      );
    }

    // console.debug('\\GeniusApi\\getAllSystems\\systemsList', systemsList);

    return systemsList;
  }

  //#endregion

  //#region PRODUCT ACTIONS

  public static async addProduct(product: IExpertProduct): Promise<number> {
    const formData = GeniusDataParser.buildFormData({
      systemId: product.systemId ?? 0,
      name: product.name ?? '',
      description: product.description ?? '',
      notes: product.notes ?? '',
    });

    // console.debug('\\GeniusApi\\addProduct\\product', product);

    const response = await fetch('api/expert/product', {
      method: 'POST',
      body: formData,
    });

    const responseText = await response.text();

    // console.debug('\\GeniusApi\\addProduct\\responseText', +responseText);

    return +responseText; //unary operator
  }

  public static async getProduct(productId: number): Promise<IExpertProduct> {
    const response = await fetch(GeniusApi.BASE_EXPERT_GATEWAY + 'product/' + productId);
    const data = await response.json();

    // console.debug('\\GeniusApi\\getProduct\\data', data);

    return await GeniusDataParser.fetchProductObject(data);
  }

  public static async updateProduct(product: IExpertProduct): Promise<boolean> {
    if (product.id < 1) {
      return false;
    }
    const formData = GeniusDataParser.buildFormData({
      id: product.id ?? 0,
      systemId: product.systemId ?? 0,
      name: product.name,
      description: product.description,
      notes: product.notes,
    });

    const response = await fetch('api/expert/product/' + product.id + '/update', {
      method: 'POST',
      body: formData,
    });

    const responseText = await response.text();

    console.debug('\\GeniusApi\\updateProduct\\responseText', responseText);

    return parseInt(responseText) > 0;
  }

  public static async deleteProduct(productId: number): Promise<boolean> {
    const response = await fetch('api/expert/product/' + productId + '/delete', {
      method: 'POST',
    });

    const responseText = await response.text();

    console.debug('\\GeniusApi\\deleteProduct\\responseText', responseText);

    return parseInt(responseText) > 0;
  }

  public static async addProductWithConditions(
    product: IExpertProduct,
    conditions: IExpertCondition[],
  ): Promise<number> {
    const systemId: number = product.systemId ?? 0;

    if (systemId < 1) {
      return 0;
    }

    let conditionIds: number[] = [];

    for (let key in conditions) {
      let conditionId: number = conditions[key].id ?? 0;

      if (conditionId > 0 && !conditionIds.includes(conditionId)) {
        conditionIds.push(conditionId);
      }
    }

    const formData = GeniusDataParser.buildFormData({
      systemId: systemId,
      name: product.name ?? '',
      description: product.description ?? '',
      notes: product.notes ?? '',
      conditions: conditionIds,
    });

    // console.debug('\\GeniusApi\\addProductWithConditions', {
    //   product: product,
    //   conditionIds: conditionIds,
    //   formData: formData,
    // });

    const response = await fetch('api/expert/product', {
      method: 'POST',
      body: formData,
    });

    const responseText = await response.text();

    console.debug('\\GeniusApi\\addProductWithConditions\\responseText', responseText);

    return +responseText;
  }

  public static async getProductRelations(productId: number): Promise<ExpertRelations> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'product/' + productId + '/relations',
    );

    const data = await response.json();

    if (!(data instanceof Object)) {
      return new ExpertRelations(productId, 0);
    }

    // console.debug('\\GeniusApi\\getProductRelations\\data', data);

    return GeniusDataParser.fetchRelationsObject(data);
  }

  public static async getProductsByIds(productIds: number[]): Promise<IExpertProduct[]> {
    let products: IExpertProduct[] = [];

    await Promise.all(
      productIds.map(async (item): Promise<void> => {
        products.push(await this.getProduct(item));
      }),
    );

    return products;
  }

  public static async updateProductConditions(
    product: IExpertProduct,
    confirming: IExpertCondition[] = [],
    negating: IExpertCondition[] = [],
    indifferent: IExpertCondition[] = [],
  ): Promise<boolean> {
    if (product.id < 0) {
      return false;
    }

    const formData = GeniusDataParser.buildFormData({
      id: product.id,
      systemId: product.systemId,
      confirming: confirming.map(e => e.id),
      negating: negating.map(e => e.id),
      indifferent: indifferent.map(e => e.id),
    });

    const response = await fetch('api/expert/product/' + product.id + '/conditions', {
      method: 'POST',
      body: formData,
    });

    const responseText = await response.text();

    return parseInt(responseText) > 0;
  }

  //#endregion

  //#region CONDITION ACTIONS

  public static async addCondition(condition: IExpertCondition): Promise<number> {
    let formData = GeniusDataParser.buildFormData({
      systemId: condition.systemId ?? 0,
      name: condition.name ?? '',
      description: condition.description ?? '',
    });

    // console.debug('\\GeniusApi\\addCondition\\condition', condition);

    let response = await fetch('api/expert/condition', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    // console.debug('\\GeniusApi\\addCondition\\responseText', responseText);

    return +responseText; //unary operator
  }

  public static async getCondition(conditionId: number): Promise<IExpertCondition> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'condition/' + conditionId,
    );
    const data = await response.json();

    console.debug('\\GeniusApi\\getCondition\\data', data);

    return await GeniusDataParser.fetchConditionObject(data);
  }

  public static async getConditionRelations(
    conditionId: number,
  ): Promise<ExpertRelations> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'condition/' + conditionId + '/relations',
    );

    const data = await response.json();

    if (!(data instanceof Object)) {
      return new ExpertRelations(conditionId, 0);
    }

    // console.debug('\\GeniusApi\\getProductRelations\\data', data);

    return GeniusDataParser.fetchRelationsObject(data);
  }

  public static async updateCondition(condition: IExpertCondition): Promise<boolean> {
    if (condition.id < 1) {
      return false;
    }

    const formData = GeniusDataParser.buildFormData({
      id: condition.id ?? 0,
      systemId: condition.systemId ?? 0,
      name: condition.name,
      description: condition.description,
    });

    const response = await fetch('api/expert/condition/' + condition.id + '/update', {
      method: 'POST',
      body: formData,
    });

    const responseText = await response.text();

    console.debug('\\GeniusApi\\updateCondition\\responseText', responseText);

    return parseInt(responseText) > 0;
  }

  public static async deleteCondition(conditionId: number): Promise<boolean> {
    const response = await fetch('api/expert/condition/' + conditionId + '/delete', {
      method: 'POST',
    });

    const responseText = await response.text();

    console.debug('\\GeniusApi\\deleteCondition\\responseText', responseText);

    return parseInt(responseText) > 0;
  }

  //#endregion

  //#region RELATION ACTIONS

  public static async addRelation(relation: IExpertRelation): Promise<number> {
    const formData = GeniusDataParser.buildFormData({
      systemId: relation.systemId ?? 0,
      conditionId: relation.conditionId,
      productId: relation.productId,
      weight: relation.weight,
    });

    // console.debug('\\GeniusApi\\addRelation\\relation', relation);

    const response = await fetch('api/expert/relation', {
      method: 'POST',
      body: formData,
    });

    const responseText = await response.text();

    // console.debug('\\GeniusApi\\addRelation\\responseText', +responseText);

    return +responseText; //unary operator
  }

  public static async getRelation(relationId: number): Promise<IExpertRelation> {
    const response = await fetch(
      GeniusApi.BASE_EXPERT_GATEWAY + 'relation/' + relationId,
    );
    const data = await response.json();

    // console.debug('\\GeniusApi\\getRelation\\data', data);

    return await GeniusDataParser.fetchRelationObject(data);
  }

  public static async updateRelation(relation: IExpertRelation): Promise<boolean> {
    //TODO: TO DO
    return false;
  }

  public static async deleteRelation(relationId: number): Promise<boolean> {
    //TODO: TO DO
    return false;
  }

  //#endregion

  //#region COMMON

  public static async importFromFile(
    importData: IImportRequest,
  ): Promise<IImportResponse> {
    const formData = GeniusDataParser.buildFormData({
      systemId: importData.systemId,
      file: importData.file,
    });

    console.debug('\\GeniusApi\\importFromFile\\importData', importData);

    const response = await fetch('api/import', {
      method: 'POST',
      body: formData,
    });

    console.debug('\\GeniusApi\\importFromFile\\response', response);

    const responseText = await response.text();

    console.debug('\\GeniusApi\\importFromFile\\responseText', responseText);

    return new ImportResponse(importData.systemId ?? 0, false, 'Unknown');
  }

  private static async fetchSystemObject(
    dataObject: any,
    fetchProducts: boolean,
    fetchConditions: boolean,
    fetchRelations: boolean,
  ): Promise<IExpertSystem> {
    const expertState = new ExpertSystem();
    expertState.id = dataObject.id ?? 0;
    expertState.guid = dataObject.guid ?? '';
    expertState.version = dataObject.version ?? '';
    expertState.name = dataObject.name ?? '';
    expertState.description = dataObject.description ?? '';
    expertState.type = dataObject.type ?? '';
    expertState.question = dataObject.question ?? '';
    expertState.createdAt = dataObject.createdAt ?? '';
    expertState.updatedAt = dataObject.updatedAt ?? '';

    expertState.products = fetchProducts
      ? await GeniusApi.getSystemProducts(expertState.id ?? 0)
      : [];

    expertState.conditions = fetchConditions
      ? await GeniusApi.getSystemConditions(expertState.id ?? 0)
      : [];

    expertState.relations = fetchRelations
      ? await GeniusApi.getSystemRelations(expertState.id ?? 0)
      : [];

    return expertState;
  }

  //#endregion
}
