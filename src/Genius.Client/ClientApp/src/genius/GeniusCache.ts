/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

export class GeniusCacheObject {
  public id: string;

  public data: any;

  public constructor(id: string, data: any) {
    this.id = id;
    this.data = data;
  }
}

/**
 * Facilitates saving the results in the memory.
 */
export class GeniusCache {
  private records: GeniusCacheObject[];

  public constructor() {
    this.records = [];
  }

  public contains(id: string): boolean {
    return this.records.filter(e => e.id === id).length > 0;
  }

  public push(id: string, data: any): void {
    if (this.contains(id)) {
      for (let index = 0; index < this.records.length; index++) {
        if (this.records[index].id === id) {
          this.records[index] = new GeniusCacheObject(id, data);
        }
      }
    } else {
      this.records.push(new GeniusCacheObject(id, data));
    }
  }

  public get(id: string): any {
    for (let index = 0; index < this.records.length; index++) {
      if (this.records[index].id === id) {
        return this.records[index].data;
      }
    }

    return null;
  }
}
