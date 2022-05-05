/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

/**
 * Represents additional information about the expert system.
 */
export interface IExpertAbout {
  /**
   * Expert system identifier.
   */
  id: number;

  /**
   * Number of products in a given system.
   */
  products: number;

  /**
   * Number of conditions in a given system.
   */
  conditions: number;

  /**
   * Number of relations in a given system.
   */
  relations: number;
}
