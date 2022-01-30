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
    const data = response.json();

    return await GeniusApi.fetchObject(
      data,
      fetchProducts,
      fetchConditions,
      fetchRelations,
    );
  }

  static async getAllSystems(): Promise<IExpertSystem[]> {
    const response = await fetch(GeniusApi.BASE_GATEWAY + 'system');
    const data = await response.json();

    return [];
  }

  static async addSystem(system: IExpertSystem): Promise<boolean> {
    if (system.systemName === '' || system.systemType === '') {
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

    return responseText === 'success';
  }

  static async getConditions(id: number): Promise<IExpertCondition[]> {
    // TODO: do
    return [];
  }

  static async getProducts(id: number): Promise<IExpertProduct[]> {
    // TODO: do
    return [];
  }

  static async addProduct(): Promise<object> {
    // TODO: do
    return {};
  }

  static async addCondition(): Promise<object> {
    // TODO: do
    return {};
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
