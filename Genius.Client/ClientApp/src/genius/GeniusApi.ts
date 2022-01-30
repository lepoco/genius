/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertState from './IExpertState';
import ExpertState from './ExpertState';

export default class GeniusApi {
  static async getSystemByGuid(guid: string): Promise<IExpertState> {
    const response = await fetch('api/expert/system/' + guid);
    const data = await response.json();

    return GeniusApi.fetchObject(data, false, false);
  }

  static async getConditions(id: number): Promise<object> {
    // TODO: do
    return {};
  }

  static async getProducts(id: number): Promise<object> {
    // TODO: do
    return {};
  }

  private static fetchObject(
    dataObject: any,
    fetchProducts: boolean = false,
    fetchConditions: boolean = false,
  ): ExpertState {
    const expertState = new ExpertState();
    expertState.systemLoaded = true;
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
      ? GeniusApi.getProducts(expertState.systemId ?? 0)
      : {};
    expertState.systemConditions = fetchConditions
      ? GeniusApi.getConditions(expertState.systemId ?? 0)
      : {};

    return expertState;
  }
}
