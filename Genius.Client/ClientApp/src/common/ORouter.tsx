/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import {
  Component as ReactComponent,
  PureComponent as ReactPureComponent,
  useRef,
} from 'react';
import type { BrowserHistory } from 'history';
import { createBrowserHistory } from 'history';
import {
  useLocation,
  useNavigate,
  useParams,
  NavigateFunction,
  Navigator,
  Params,
  Location,
} from 'react-router-dom';

export namespace ORouter {
  /**
   * Ugly static way to make React component classes variable from a router.
   * @param Component Instance of React component.
   * @returns JSX.Element with DOM Router parameters applied.
   */
  export function bind(Component: any) {
    return props => (
      <Component
        {...props}
        router={{
          location: useLocation(),
          navigate: useNavigate(),
          params: useParams(),
          navigator:
            useRef<BrowserHistory>()?.current ?? createBrowserHistory({ window }),
        }}
      />
    );
  }

  /**
   * Contains the router elements that will be added after binding.
   */
  export interface IRouter {
    location: Location;
    navigate: NavigateFunction;
    params: Params<any>;
    navigator: Navigator;
  }

  /**
   * Base routed Component properties.
   */
  export interface IRouterProps {
    router: IRouter;
  }

  /**
   * Required interface for a navigable Component.
   */
  export interface INavigableComponent {
    navigated?(): void;
  }

  /**
   * Contains the logic for a component that is part of the DOM router.
   */
  export class Component<S = {}>
    extends ReactComponent<IRouterProps, S>
    implements INavigableComponent
  {
    private currentPath: string = '\\';
    public router: IRouter;

    public constructor(props: IRouterProps) {
      super(props);

      this.router = props.router;
      this.currentPath = props.router.location.pathname;
    }

    /**
     * Triggered when the Component has been navigated by a router.
     */
    public navigated(): void {}

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
    public shouldComponentUpdate(
      nextProps: Readonly<IRouterProps>,
      nextState: Readonly<any>,
      nextContext: any,
    ): boolean {
      if (nextProps.router !== undefined) {
        this.router = nextProps.router;
      }

      return true;
    }
  }

  export class PureComponent<S = {}>
    extends ReactPureComponent<IRouterProps, S>
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
  }
}
