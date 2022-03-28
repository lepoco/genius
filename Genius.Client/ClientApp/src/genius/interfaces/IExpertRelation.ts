/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

/**
 * Represents an expert system relation between Product and Condition.
 */
export default interface IExpertRelation {
  /**
   * Relation identifier.
   */
  id: number;

  /**
   * Expert system identifier.
   */
  systemId: number;

  /**
   * Condition identifier.
   */
  conditionId: number;

  /**
   * Product identifier.
   */
  productId: number;

  /**
   * The importance of the relationship.
   */
  weight: number;
}
