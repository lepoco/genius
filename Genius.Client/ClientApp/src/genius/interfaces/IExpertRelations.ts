/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertRelation } from './IExpertRelation';

/**
 * Represents relations and their type of belonging to a given element.
 */
export interface IExpertRelations {
  /**
   * Selected element identifier.
   */
  id: number;

  /**
   * Expert system identifier.
   */
  systemId: number;

  /**
   * Relations is met by a given element, and therefore belongs to it.
   */
  confirming: number[];

  /**
   * Relations negates the given element, so it does not belong to it.
   */
  negating: number[];

  /**
   * Relations neutral for a given element, so it may or may not belong to it.
   */
  indifferent: number[];

  /**
   * Collects all ID's of relations to one array.
   * @returns All relations ID's for a given element.
   */
  getAll(): number[];

  /**
   * Calculates the total number of all relationships.
   * @returns Total number of all relationships.
   */
  count(): number;
}
