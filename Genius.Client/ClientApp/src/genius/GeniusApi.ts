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

    console.debug('\\GeniusApi\\getSystemByGuid', data);

    return await GeniusApi.fetchObject(
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
        await GeniusApi.fetchObject(
          data[key],
          fetchProducts,
          fetchConditions,
          fetchRelations,
        ),
      );
    }

    console.debug('\\GeniusApi\\getAllSystems', systemsList);

    return systemsList;
  }

  /**
   * Adds a new expert system to the database.
   * @param system New system to be added.
   * @returns true if the operation was successful.
   */
  static async addSystem(system: IExpertSystem): Promise<boolean> {
    if (system.systemName === '' || system.systemType === '') {
      console.debug('\\GeniusApi\\addSystem', 'name or type empty');

      return false;
    }

    let formData = GeniusApi.buildFormData({
      name: system.systemName,
      description: system.systemDescription,
      question: system.systemQuestion,
      type: system.systemType,
    });

    let response = await fetch('api/expert/system', {
      method: 'POST',
      body: formData,
    });

    let responseText = await response.text();

    console.debug('\\GeniusApi\\addSystem', responseText);

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
    // TODO: do
    return [];
  }

  static async addCondition(condition: IExpertCondition): Promise<number> {
    // TODO: do
    return 0;
  }

  /**
   * Retrieves the products assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of products assigned to the system.
   */
  static async getProducts(id: number): Promise<IExpertProduct[]> {
    // TODO: do
    return [];
  }

  static async addProduct(product: IExpertProduct): Promise<number> {
    // TODO: do
    return 0;
  }

  /**
   * Retrieves the relations assigned to the given expert system.
   * @param id Expert system identifier.
   * @returns List of relations assigned to the system.
   */
  static async getRelations(id: number): Promise<IExpertProduct[]> {
    // TODO: do
    return [];
  }

  static async addRelation(relation: IExpertRelation): Promise<number> {
    // TODO: do
    return 0;
  }

  private static async fetchObject(
    dataObject: any,
    fetchProducts: boolean,
    fetchConditions: boolean,
    fetchRelations: boolean,
  ): Promise<ExpertSystem> {
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

  private static buildFormData(data: object): FormData {
    const formData = new FormData();

    for (let key in data) {
      formData.append(key, data[key]);
    }

    return formData;
  }
}
