/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { GeniusApi } from './GeniusApi';
import { GeniusSolver } from './GeniusSolver';
import { GeniusDataParser } from './GeniusDataParser';

export type { IExpertSystem } from './interfaces/IExpertSystem';
export type { IExpertAbout } from './interfaces/IExpertAbout';
export type { IExpertProduct } from './interfaces/IExpertProduct';
export type { IExpertCondition } from './interfaces/IExpertCondition';
export type { IExpertRelation } from './interfaces/IExpertRelation';
export type { IExpertRelations } from './interfaces/IExpertRelations';
export type { IImportRequest } from './interfaces/IImportRequest';
export type { IImportResponse } from './interfaces/IImportResponse';
export type { ISolverQuestion } from './interfaces/ISolverQuestion';
export type { ISolverResponse } from './interfaces/ISolverResponse';
export type { ISolverResultingProduct } from './interfaces/ISolverResultingProduct';

export { ExpertSystem } from './ExpertSystem';
export { ExpertAbout } from './ExpertAbout';
export { ExpertProduct } from './ExpertProduct';
export { ExpertCondition } from './ExpertCondition';
export { ExpertRelation } from './ExpertRelation';
export { ExpertRelations } from './ExpertRelations';
export { ImportRequest } from './ImportRequest';
export { ImportResponse } from './ImportResponse';
export { SolverQuestion } from './SolverQuestion';
export { SolverResponse } from './SolverResponse';
export { SolverResultingProduct } from './SolverResultingProduct';

export namespace Genius {
  /**
   * Contains logic responsible for polling the internal API that connects via gRPC to the Genius microservice.
   */
  export class Api extends GeniusApi {}

  /**
   * Contains methods for solving the expert system.
   */
  export class Solver extends GeniusSolver {}

  /**
   * Contains static methods that cast objects to the expected interfaces.
   */
  export class Parser extends GeniusDataParser {}

  /**
   * Variants under which the condition may belong to the product.
   */
  export enum ConditionType {
    /**
     * The condition is met by a given product, and therefore belongs to it.
     */
    Confirming,

    /**
     * The condition negates the given product, so it does not belong to it.
     */
    Negating,

    /**
     * The condition is neutral for a given product, so it may or may not belong to it.
     */
    Indifferent,
  }
}
