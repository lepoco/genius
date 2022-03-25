/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import IExpertProduct from './interfaces/IExpertProduct';

export default class ExpertProduct implements IExpertProduct {
  public id: number = 0;
  public system_id: number = 0;
  public name: string = '';
  public description: string = '';
  public notes: string = '';

  public constructor(
    id: number,
    system_id: number = 0,
    name: string = '',
    description: string = '',
    notes: string = '',
  ) {
    this.id = id;
    this.system_id = system_id;
    this.name = name;
    this.description = description;
    this.notes = notes;
  }
}
