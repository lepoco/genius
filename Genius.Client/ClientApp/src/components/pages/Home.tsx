/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';

/**
 * Home page Component.
 */
export default class Home extends PureComponent {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Home.name;

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <div className="container -pt-5">
        <div className="row">
          <div className="col-12 col-lg-6 -pb-3 -mh-70 -flex-center">
            <div>
              <h2 className="-font-secondary -fw-700">
                Artificial intelligence made simple
              </h2>
              <p>Genius is a tool for creating expert systems.</p>
            </div>
          </div>
          <div className="col-12 col-lg-6 -flex-center">
            <img
              src="img/mind-circuit.png"
              alt="Genius front page"
              style={{ width: '100%' }}
            />
          </div>
        </div>
      </div>
    );
  }
}
