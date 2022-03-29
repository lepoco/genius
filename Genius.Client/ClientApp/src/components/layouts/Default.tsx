/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';
import { ToastContainer, ToastProvider } from '../common/Toasts';
import NavMenu from '../common/NavMenu';
import Footer from '../common/Footer';

/**
 * The default application layout Component.
 */
export class Default extends PureComponent {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Default.name;

  /**
   * Called immediately after a component is mounted. Setting state here will trigger re-rendering.
   */
  public async componentDidMount(): Promise<boolean> {
    return true;
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <>
        <NavMenu />
        <section className="main">
          <div className="container">{this.props.children}</div>
        </section>
        <ToastContainer ref={e => ToastProvider.bind(e)} />
        <Footer />
      </>
    );
  }
}
