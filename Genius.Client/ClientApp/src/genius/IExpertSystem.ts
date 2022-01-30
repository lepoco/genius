/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */
import IExpertCondition from './IExpertCondition';
import IExpertProduct from './IExpertProduct';

export default interface IExpertSystem {
  systemId?: number;
  systemGuid?: string;
  systemVersion?: string;
  systemName?: string;
  systemDescription?: string;
  systemType?: string;
  systemQuestion?: string;
  systemCreatedAt?: string;
  systemUpdatedAt?: string;

  systemConditions?: IExpertCondition[];
  systemProducts?: IExpertProduct[];
}
