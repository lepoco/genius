/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertState from './IExpertState';

export default class ExpertState implements IExpertState {
  systemLoaded: boolean = false;
  systemId: number = 0;
  systemGuid: string = '';
  systemVersion: string = '';
  systemName: string = '';
  systemDescription: string = '';
  systemType: string = '';
  systemQuestion: string = '';
  systemCreatedAt: string = '';
  systemUpdatedAt: string = '';
  systemConditions: object = {};
  systemProducts: object = {};
}
