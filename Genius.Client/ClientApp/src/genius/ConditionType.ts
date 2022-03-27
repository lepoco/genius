/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

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
