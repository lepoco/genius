/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { GeniusApi } from './GeniusApi';
import { GeniusDataParser } from './GeniusDataParser';

import { ISolverQuestion } from './interfaces/ISolverQuestion';
import { ISolverResponse } from './interfaces/ISolverResponse';
import { SolverResponse } from './SolverResponse';

/**
 * Contains methods for solving the expert system.
 */
export class GeniusSolver {
  private static readonly BASE_SOLVER_GATEWAY: string = '/api/solver/';

  /**
   * Asks a question to the service solving expert systems.
   * @param question Terms of the question
   * @returns Response from solver.
   */
  public static async ask(question: ISolverQuestion): Promise<ISolverResponse> {
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

    console.debug('\\GeniusSolver\\ask\\responseData', responseData);

    return await this.fetchResponseObject(responseData);
  }

  private static async fetchResponseObject(dataObject: any): Promise<ISolverResponse> {
    const products = await GeniusApi.getProductsByIds(
      Array.isArray(dataObject.products) ? dataObject.products : [],
    );

    const nextCondition = await GeniusApi.getCondition(
      parseInt(dataObject.nextCondition ?? 0),
    );

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
