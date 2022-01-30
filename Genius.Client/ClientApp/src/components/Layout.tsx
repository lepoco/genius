/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';
import NavMenu from './NavMenu';
import Footer from './Footer';

export class Layout extends Component {
  static displayName = Layout.name;

  render() {
    return (
      <div id="app">
        <NavMenu />
        <div className="container">{this.props.children}</div>
        <Footer />
      </div>
    );
  }
}
