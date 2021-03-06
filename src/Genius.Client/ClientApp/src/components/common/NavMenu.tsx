/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';
import { Link, NavLink } from 'react-router-dom';

interface INavMenuProps {}

interface INavMenuState {
  navbarCollapsed: boolean;
}

/**
 * Navigation Component for Default layout.
 */
export default class NavMenu extends PureComponent<INavMenuProps, INavMenuState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = NavMenu.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the parent element.
   */
  public constructor(props: INavMenuProps) {
    super(props);

    this.state = {
      navbarCollapsed: true,
    };

    this.navButtonOnClick = this.navButtonOnClick.bind(this);
    this.togglerButtonOnClick = this.togglerButtonOnClick.bind(this);
  }

  /**
   * Called immediately after a component is mounted. Setting state here will trigger re-rendering.
   */
  public async componentDidMount(): Promise<boolean> {
    // window.onload = function (this: GlobalEventHandlers, event: Event) {
    //   // TODO: Causes console error
    //   new BootstrapDropdown();
    // };

    return true;
  }

  public async togglerButtonOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    this.setState({ navbarCollapsed: !this.state.navbarCollapsed });

    return true;
  }

  public async navButtonOnClick(
    event: React.MouseEvent<HTMLAnchorElement, MouseEvent>,
  ): Promise<boolean> {
    this.setState({
      navbarCollapsed: true,
    });

    return true;
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <section className="navbar navbar-expand-lg navbar-light">
        <div className="container">
          <Link className="navbar-brand" to="/">
            <p>Genius</p>
          </Link>
          <button
            onClick={e => this.togglerButtonOnClick(e)}
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div
            className={`${this.state.navbarCollapsed ? 'collapse' : ''} navbar-collapse`}
            id="navbarSupportedContent">
            <ul className="navbar-nav me-auto mb-2 mb-lg-0"></ul>
            <div className="d-flex -lg-mr-2">
              <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                <li className="nav-item">
                  <NavLink
                    onClick={e => this.navButtonOnClick(e)}
                    className="nav-link"
                    to={'/dashboard'}
                    end={true}>
                    Dashboard
                  </NavLink>
                </li>

                <li className="nav-item">
                  <NavLink
                    onClick={e => this.navButtonOnClick(e)}
                    className="nav-link"
                    to={'/dashboard/add'}>
                    Add new
                  </NavLink>
                </li>

                <li className="nav-item">
                  <Link
                    onClick={e => this.navButtonOnClick(e)}
                    className="nav-link"
                    to={'/dashboard/account'}>
                    Account
                  </Link>
                </li>

                {/* <li className="nav-item">
                  <Link className="nav-link" to={'/dashboard/statistics'}>
                    Statistics
                  </Link>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to={'/dashboard/users'}>
                    Users
                  </Link>
                </li> */}
                <li className="nav-item">
                  <Link
                    onClick={e => this.navButtonOnClick(e)}
                    className="nav-link"
                    to={'/dashboard/settings'}>
                    Settings
                  </Link>
                </li>
              </ul>
            </div>
            <div className="d-flex">
              {/* <a href="/signin" className="btn btn-outline-dark btn-mobile -lg-mr-1" type="submit">Sign in</a>
              <a href="/register" className="btn btn-dark btn-mobile" type="submit">Register for free</a> */}
            </div>
          </div>
        </div>
      </section>
    );
  }
}
