/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';
import withRouter from './../common/withRouter';
import IRouterProps from './../interfaces/IRouterProps';
import IRouter from './../interfaces/IRouter';
import { Link } from 'react-router-dom';
import { Dropdown as BootstrapDropdown } from 'bootstrap';

class NavMenu extends Component<IRouterProps> {
  static displayName = NavMenu.name;

  router: IRouter;

  constructor(props: IRouterProps) {
    super(props);

    this.router = props.router;

    window.onload = function (e) {
      new BootstrapDropdown();
    };
  }

  // constructor (props) {
  //   super(props);

  //   this.toggleNavbar = this.toggleNavbar.bind(this);
  //   this.state = {
  //     collapsed: true
  //   };
  // }

  // toggleNavbar () {
  //   this.setState({
  //     collapsed: !this.state.collapsed
  //   });
  // }

  render() {
    return (
      <section className="navbar navbar-expand-lg navbar-light">
        <div className="container">
          <Link className="navbar-brand" to={'/'}>
            <p>Genius</p>
          </Link>
          <button
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse" id="navbarSupportedContent">
            <ul className="navbar-nav me-auto mb-2 mb-lg-0"></ul>
            <div className="d-flex -lg-mr-2">
              <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                <li className="nav-item">
                  <Link className="nav-link" to={'/dashboard'}>
                    Dashboard
                  </Link>
                </li>

                <li className="nav-item">
                  <Link className="nav-link" to={'/dashboard/add'}>
                    Add new
                  </Link>
                </li>

                <li className="nav-item">
                  <Link className="nav-link" to={'/dashboard/account'}>
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
                  <Link className="nav-link" to={'/dashboard/settings'}>
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

export default withRouter(NavMenu);
