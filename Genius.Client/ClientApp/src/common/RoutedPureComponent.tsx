/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { PureComponent } from 'react';
import INavigableComponent from './../interfaces/INavigableComponent';
import IRouterProps from './../interfaces/IRouterProps';
import IRouter from './../interfaces/IRouter';

/**
 * Contains the logic for a component that is part of the DOM router.
 */
export default class RoutedComponent<S = {}>
  extends PureComponent<IRouterProps, S>
  implements INavigableComponent
{
  private currentPath: string = '\\';

  public router: IRouter;

  public constructor(props: IRouterProps) {
    super(props);

    this.router = props.router;
  }

  /**
   * Triggered when the Component has been navigated by a router.
   */
  public navigated() {}

  /**
   * Forces the window to reload and loses the current application state.
   */
  public reload(): void {
    if (this.router?.navigator === undefined) {
      window.location.reload();

      return;
    }

    this.router.navigator.go(0);
    //this.router.navigator.push(this.router.location.pathname, null);
  }

  /**
   * Called immediately after a component is mounted. Setting state here will trigger re-rendering.
   */
  public componentDidMount(): void {
    if (this.router.location.pathname !== this.currentPath) {
      this.currentPath = this.router.location.pathname;
      this.navigated();
    }
  }

  /**
   * Called immediately after updating occurs. Not called for the initial render.
   * The snapshot is only present if getSnapshotBeforeUpdate is present and returns non-null.
   */
  public componentDidUpdate(
    prevProps: Readonly<IRouterProps>,
    prevState: Readonly<any>,
    snapshot?: any,
  ): void {}

  /**
   * Runs before React applies the result of `render` to the document, and
   * returns an object to be given to componentDidUpdate. Useful for saving
   * things such as scroll position before `render` causes changes to it.
   *
   * Note: the presence of getSnapshotBeforeUpdate prevents any of the deprecated
   * lifecycle events from running.
   */
  public getSnapshotBeforeUpdate(
    prevProps: Readonly<IRouterProps>,
    prevState: Readonly<any>,
  ): Readonly<any> {
    if (this.router.location.pathname !== this.currentPath) {
      this.currentPath = this.router.location.pathname;
      this.navigated();
    }

    return prevState;
  }

  // Pure Component should not use shouldComponentUpdate

  /**
   * Called to determine whether the change in props and state should trigger a re-render.
   *
   * `Component` always returns true.
   * `PureComponent` implements a shallow comparison on props and state and returns true if any
   * props or states have changed.
   *
   * If false is returned, `Component#render`, `componentWillUpdate`
   * and `componentDidUpdate` will not be called.
   */
  // public shouldComponentUpdate(
  //   nextProps: Readonly<IRouterProps>,
  //   nextState: Readonly<any>,
  //   nextContext: any,
  // ): boolean {
  //   if (nextProps.router !== undefined) {
  //     this.router = nextProps.router;
  //   }

  //   return true;
  // }
}
