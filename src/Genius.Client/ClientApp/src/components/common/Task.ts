/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

export interface TaskDelegate {
  (): boolean;
}

/**
 * Represents an asynchronous operation.
 */
export default class Task {
  /**
   * Creates a task that will complete after a time delay.
   *
   * @param millisecondsDelay The number of milliseconds to wait before completing the returned task.
   */
  public static delay(millisecondsDelay: number) {
    return new Promise(resolve => setTimeout(resolve, millisecondsDelay));
  }

  public static run(delegate: TaskDelegate): void {
    new Promise(resolve => {
      delegate();
    }).then(e => {});
  }
}
