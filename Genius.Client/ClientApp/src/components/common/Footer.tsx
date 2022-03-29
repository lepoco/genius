/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';
import { Link } from 'react-router-dom';

/**
 * Footer Component for Default layout.
 */
export default class Footer extends PureComponent {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Footer.name;

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <section className="expanded-footer">
        <div className="container py-4">
          <div className="row">
            <div className="col-12 col-lg-4">
              <h4 className="-mb-4">Genius</h4>
            </div>

            <div className="col-12"></div>

            <div className="expanded-footer__list col-12">
              <ul className="list-inline">
                <li className="list-inline-item">
                  &copy; {new Date().getFullYear()} dev.lepo.co
                </li>
                <li className="list-inline-item">
                  <Link to={'/terms'}>Website Terms</Link>
                </li>
                <li className="list-inline-item">
                  <Link to={'/legal'}>Legal Agreements</Link>
                </li>
                <li className="list-inline-item">
                  <Link to={'/privacy'}>Privacy Policy</Link>
                </li>
                <li className="list-inline-item">
                  <a
                    href="https://github.com/lepoco/Genius"
                    target="_blank"
                    rel="noreferrer noopener">
                    Source Code
                  </a>
                </li>
                <li className="list-inline-item">
                  <Link to={'/licenses'}>Licenses</Link>
                </li>
              </ul>
            </div>

            <div className="expanded-footer__bottom col-12">
              If you would like to find out more about which genius entity you receive
              services from, or if you have any other questions, please reach out to us
              via the help@genius.lepo.co email.
            </div>
          </div>
        </div>
      </section>
    );
  }
}
