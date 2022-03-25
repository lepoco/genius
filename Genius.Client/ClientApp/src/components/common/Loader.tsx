/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';

interface ILoaderProps {
  center: boolean;
}

interface ILoaderState {
  center: boolean;
}

export default class Loader extends PureComponent<ILoaderProps, ILoaderState> {
  static displayName: string = Loader.name;

  public constructor(props: ILoaderProps) {
    super(props);
    this.state = {
      center: props.center ?? false,
    };
  }

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
