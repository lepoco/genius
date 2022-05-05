/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';

/**
 * Represents the values passed as Component attributes.
 */
interface ILoaderProps {
  center?: boolean;
}

/**
 * Represents the variables contained in the Component state.
 */
interface ILoaderState {
  center: boolean;
}

export default class Loader extends PureComponent<ILoaderProps, ILoaderState> {
  /**
   * The display name of the Component.
   */
  static displayName: string = Loader.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the parent element.
   */
  public constructor(props: ILoaderProps) {
    super(props);

    this.state = {
      center: props.center ?? false,
    };
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <div className={'loading-boxes' + (this.state.center ? ' -w-20' : '')}>
        <div className="loading-boxes__box">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div className="loading-boxes__box">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div className="loading-boxes__box">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div className="loading-boxes__box">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
    );
  }
}
