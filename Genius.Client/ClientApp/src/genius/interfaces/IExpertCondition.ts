/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

/**
 * Represents an expert system condition.
 */
export default interface IExpertCondition {
  /**
   * Condition identifier.
   */
  id: number;

  /**
   * Expert system identifier.
   */
  systemId: number;

  /**
   * Condition name.
   */
  name: string;

  /**
   * Condition description.
   */
  description: string;
}
