/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';

export class Legal extends Component {
  static displayName: string = Legal.name;

  public render(): JSX.Element {
    return (
      <div className="container -pt-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">
              Legal Agreements
            </h4>
          </div>

          <div className="col-12 -mb-5">
            <p className="-reveal">
              Genius is not a real website. It's just a college project. Don't
              use it.
            </p>
          </div>
        </div>
      </div>
    );
  }
}
