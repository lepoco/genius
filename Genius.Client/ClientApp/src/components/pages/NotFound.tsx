/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';

export default class NotFound extends PureComponent {
  public static displayName: string = NotFound.name;

  public render(): JSX.Element {
    return (
      <div className="container -pt-5">
        <div className="row">
          <div className="col-12 col-lg-6 -pb-3 -mh-70 -flex-center -reveal">
            <div>
              <h2 className="-font-secondary -fw-700">Page not found</h2>
              <p>We can't find the page you are looking for.</p>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
