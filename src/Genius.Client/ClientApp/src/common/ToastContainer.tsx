/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';

/**
 * Contains the logic for a component that is part of the DOM router.
 */
export default class ToastContainer extends Component {
  public static sendNotification(header: string, message: string): void {}

  public render(): JSX.Element {
    const notifications = [];

    return <section className="toast__container">{notifications}</section>;
  }
}
