/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertSystem from './IExpertSystem';
import IExpertCondition from './IExpertCondition';
import IExpertProduct from './IExpertProduct';
import ExpertSystem from './ExpertSystem';
import IExpertRelation from './IExpertRelation';
import ExpertCondition from './ExpertCondition';
import ExpertProduct from './ExpertProduct';
import ExpertRelation from './ExpertRelation';
import System from '../components/dashboard/System';

/**
 * Contains logic responsible for polling the internal API that connects via gRPC to the Genius microservice.
 */
export default class GeniusApi {
  private static readonly BASE_GATEWAY: string = '/api/expert/';

  /**
   * Get the system by its GUID.
   * @param guid Universal identifier of system in the database.
   * @param fetchProducts Whether the call should also fetch the system products.
   * @param fetchConditions Whether the call should also fetch the system conditions.
   * @param fetchRelations Whether the call should also fetch the system relations.
   * @returns Instance of expert system.
   */
  static async getSystemByGuid(
    guid: string,
    fetchProducts: boolean = false,
    fetchConditions: boolean = false,
    fetchRelations: boolean = false,
  ): Promise<IExpertSystem> {
    const response = await fetch(GeniusApi.BASE_GATEWAY + 'system/' + guid);
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
   * Retrieves all known expert systems from the database.
   * @param fetchProducts Whether the call should also fetch the system products.
   * @param fetchConditions Whether the call should also fetch the system conditions.
   * @param fetchRelations Whether the call should also fetch the system relations.
   * @returns
   */
  static async getAllSystems(
    fetchProducts: boolean = false,
    fetchConditions: boolean = false,
    fetchRelations: boolean = false,
  ): Promise<IExpertSystem[]> {
    const response = await fetch(GeniusApi.BASE_GATEWAY + 'system');
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

    console.debug('\\GeniusApi\\getAllSystems\\systemsList', systemsList);

    return systemsList;
  }

  /**
   * Adds a new expert system to the database.
   * @param system New system to be added.
   * @returns true if the operation was successful.
   */
  static async addSystem(system: IExpertSystem): Promise<boolean> {
    if (system.systemName === '' || system.systemType === '') {
      console.debug('\\GeniusApi\\addSystem', false);

      return false;
    }

    let formData = GeniusApi.buildFormData({
      name: system.systemName ?? '',
      description: system.systemDescription ?? '',
      question: system.systemQuestion ?? '',
      type: system.systemType ?? '',
    });

    let response = await fetch('api/expert/system', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addSystem\\responseText', responseText);

    return responseText === 'success';
  }

  /**
   * Tries to remove the expert system with the given ID.
   * @param id Expert system identifier.
   * @returns true if the operation was successful.
   */
  static async deleteSystem(id: number): Promise<boolean> {
    const response = await fetch(GeniusApi.BASE_GATEWAY + 'system/' + id, {
      method: 'DELETE',
    });

    console.debug('\\GeniusApi\\deleteSystem\\id', id);

    const responseText = await response.text();

    console.debug('\\GeniusApi\\deleteSystem\\responseText', responseText);

    return responseText === 'success';
  }

  /**
   * Retrieves the conditions assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of conditions assigned to the system.
   */
  static async getConditions(id: number): Promise<IExpertCondition[]> {
    const response = await fetch(
      GeniusApi.BASE_GATEWAY + 'system/' + id + '/conditions',
    );
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let conditionsList: IExpertCondition[] = [];

    for (let key in data) {
      conditionsList.push(GeniusApi.fetchConditionObject(data[key]));
    }

    console.debug('\\GeniusApi\\getConditions\\conditionsList', conditionsList);

    return conditionsList;
  }

  static async getCondition(conditionId: number): Promise<IExpertCondition> {
    const response = await fetch(
      GeniusApi.BASE_GATEWAY + 'condition/' + conditionId,
    );
    const data = await response.json();

    console.debug('\\GeniusApi\\getCondition\\data', data);

    return await GeniusApi.fetchConditionObject(data);
  }

  static async addCondition(condition: IExpertCondition): Promise<number> {
    let formData = GeniusApi.buildFormData({
      systemId: condition.system_id ?? 0,
      name: condition.name ?? '',
      description: condition.description ?? '',
    });

    console.debug('\\GeniusApi\\addCondition\\condition', condition);

    let response = await fetch('api/expert/condition', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addCondition\\responseText', responseText);

    return +responseText; //unary operator
  }

  /**
   * Retrieves the products assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of products assigned to the system.
   */
  static async getProducts(id: number): Promise<IExpertProduct[]> {
    const response = await fetch(
      GeniusApi.BASE_GATEWAY + 'system/' + id + '/products',
    );
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let productsList: IExpertProduct[] = [];

    for (let key in data) {
      productsList.push(GeniusApi.fetchProductObject(data[key]));
    }

    console.debug('\\GeniusApi\\getProducts\\productsList', productsList);

    return productsList;
  }

  static async getProduct(productId: number): Promise<IExpertCondition> {
    const response = await fetch(
      GeniusApi.BASE_GATEWAY + 'product/' + productId,
    );
    const data = await response.json();

    console.debug('\\GeniusApi\\getProduct\\data', data);

    return await GeniusApi.fetchProductObject(data);
  }

  static async addProduct(product: IExpertProduct): Promise<number> {
    let formData = GeniusApi.buildFormData({
      systemId: product.system_id ?? 0,
      name: product.name ?? '',
      description: product.description ?? '',
      notes: product.notes ?? ''
    });

    console.debug('\\GeniusApi\\addProduct\\product', product);

    let response = await fetch('api/expert/product', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addProduct\\responseText', +responseText);

    return +responseText; //unary operator
  }

  static async addProductWithConditions(
    product: IExpertProduct,
    conditions: IExpertCondition[],
  ): Promise<number> {

    let systemId: number = product.system_id ?? 0;

    if (systemId < 1) {
      return 0;
    }

    let conditionIds:number[] = [];

    for (let key in conditions) {
      let conditionId: number = conditions[key].id ?? 0;

      if (conditionId > 0 && !conditionIds.includes(conditionId)) {
        conditionIds.push(conditionId);
      }
    }

    let formData = GeniusApi.buildFormData({
      systemId: systemId,
      name: product.name ?? '',
      description: product.description ?? '',
      notes: product.notes ?? '',
      conditions: conditionIds
    });

    console.debug('\\GeniusApi\\addProductWithConditions', {
      product: product,
      conditionIds: conditionIds,
      formData: formData
    });

    let response = await fetch('api/expert/product', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addProductWithConditions\\responseText', +responseText);

    return +responseText;
  }

  /**
   * Retrieves the relations assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of relations assigned to the system.
   */
  static async getRelations(id: number): Promise<IExpertProduct[]> {
    const response = await fetch(
      GeniusApi.BASE_GATEWAY + 'system/' + id + '/relations',
    );
    const data = await response.json();

    if (!(data instanceof Object)) {
      return [];
    }

    let relationsList: IExpertRelation[] = [];

    for (let key in data) {
      relationsList.push(GeniusApi.fetchRelationObject(data[key]));
    }

    console.debug('\\GeniusApi\\getRelations\\relationsList', relationsList);

    return relationsList;
  }

  static async getRelation(relationId: number): Promise<IExpertCondition> {
    const response = await fetch(
      GeniusApi.BASE_GATEWAY + 'relation/' + relationId,
    );
    const data = await response.json();

    console.debug('\\GeniusApi\\getRelation\\data', data);

    return await GeniusApi.fetchProductObject(data);
  }

  static async addRelation(relation: IExpertRelation): Promise<number> {
    let formData = GeniusApi.buildFormData({
      systemId: relation.systemId,
      conditionId: relation.conditionId,
      productId: relation.productId,
      weight: relation.weight,
    });

    console.debug('\\GeniusApi\\addRelation\\relation', relation);

    let response = await fetch('api/expert/relation', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addRelation\\responseText', +responseText);

    return +responseText; //unary operator
  }

  private static async fetchSystemObject(
    dataObject: any,
    fetchProducts: boolean,
    fetchConditions: boolean,
    fetchRelations: boolean,
  ): Promise<IExpertSystem> {
    const expertState = new ExpertSystem();
    expertState.systemId = dataObject.id ?? 0;
    expertState.systemGuid = dataObject.guid ?? '';
    expertState.systemVersion = dataObject.version ?? '';
    expertState.systemName = dataObject.name ?? '';
    expertState.systemDescription = dataObject.description ?? '';
    expertState.systemType = dataObject.type ?? '';
    expertState.systemQuestion = dataObject.question ?? '';
    expertState.systemCreatedAt = dataObject.createdAt ?? '';
    expertState.systemUpdatedAt = dataObject.updatedAt ?? '';

    expertState.systemProducts = fetchProducts
      ? await GeniusApi.getProducts(expertState.systemId ?? 0)
      : [];

    expertState.systemConditions = fetchConditions
      ? await GeniusApi.getConditions(expertState.systemId ?? 0)
      : [];

    expertState.systemRelations = fetchRelations
      ? await GeniusApi.getRelations(expertState.systemId ?? 0)
      : [];

    return expertState;
  }

  private static fetchConditionObject(dataObject: any): IExpertCondition {
    return new ExpertCondition(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.name ?? '',
      dataObject.description ?? '',
    );
  }

  private static fetchProductObject(dataObject: any): IExpertProduct {
    return new ExpertProduct(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.name ?? '',
      dataObject.description ?? '',
      dataObject.notes ?? '',
    );
  }

  private static fetchRelationObject(dataObject: any): IExpertRelation {
    return new ExpertRelation(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.conditionId ?? 0,
      dataObject.productId ?? 0,
      dataObject.weight ?? 100,
    );
  }

  private static buildFormData(data: object): FormData {
    const formData = new FormData();

    for (let key in data) {
      formData.append(key, data[key]);
    }

    return formData;
  }
}
