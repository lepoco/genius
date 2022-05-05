/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';
import {
  CheckmarkStarburst16Regular,
  ShieldError16Regular,
  Comment16Regular,
} from '@fluentui/react-icons';

declare global {
  interface Window {
    __toast_hook?: ToastContainer;
  }
}

export class ToastProvider {
  public static show(
    name: string,
    message: string,
    timeout: number = 5000,
    type: ToastType | null = null,
  ): boolean {
    if (window.__toast_hook === undefined) {
      return false;
    }

    window.__toast_hook?.show(new Toast(name, message, timeout, type));

    return true;
  }

  public static bind(container: ToastContainer | null): void {
    if (container === null) {
      return;
    }

    window.__toast_hook = container;
  }
}

/**
 * Type of notifications affecting their graphic appearance.
 */
export enum ToastType {
  Default,
  Success,
  Error,
}

/**
 * Represents a single notification.
 */
export class Toast {
  name: string;

  message: string;

  timeout: number;

  type: ToastType;

  public constructor(
    name: string,
    message: string,
    timeout: number = 5000,
    type: ToastType | null = null,
  ) {
    this.name = name;
    this.message = message;
    this.timeout = timeout;
    this.type = type ?? ToastType.Default;
  }
}

/**
 * Represents the values passed as Component attributes.
 */
interface IToastContainerProps {}

/**
 * Represents the variables contained in the Component state.
 */
interface IToastContainerState {
  current: Toast;
}

/**
 * Component containing a field for displaying notifications.
 */
export class ToastContainer extends Component<
  IToastContainerProps,
  IToastContainerState
> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = ToastContainer.name;

  /**
   * A collection of all notifications sent in a given SPA session.
   */
  private toasts: Toast[];

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the parent element.
   */
  public constructor(props: IToastContainerProps) {
    super(props);

    this.toasts = [];

    this.state = {
      current: new Toast('', ''),
    };

    this.hideToast = this.hideToast.bind(this);
    this.closeButtonOnClick = this.closeButtonOnClick.bind(this);
  }

  /**
   * Displays the given notification.
   */
  public show(notification: Toast): void {
    this.toasts.push(notification);

    this.setState({ current: notification });
  }

  private async closeButtonOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    // TODO: Remove default bootstrap behavior
    return true;
  }

  private hideToast(element: HTMLDivElement | null, timeout: number): void {
    if (element === null) {
      return;
    }

    setTimeout(() => {
      if (element === undefined || element === null) {
        return;
      }

      element.classList.add('hide');
      element.classList.remove('show');
    }, timeout);
  }

  private renderDate(): JSX.Element {
    const currentDate: Date = new Date();
    const formattedDate: string =
      (currentDate.getDate() < 10 ? '0' + currentDate.getDate() : currentDate.getDate()) +
      '.' +
      (currentDate.getMonth() < 10
        ? '0' + currentDate.getMonth()
        : currentDate.getMonth()) +
      '.' +
      currentDate.getFullYear() +
      ' ' +
      currentDate.getHours() +
      ':' +
      currentDate.getMinutes();

    return <>{formattedDate}</>;
  }

  private renderToast(currentToast: Toast, index: number): JSX.Element {
    if (currentToast.name === '' && currentToast.message === '') {
      return <></>;
    }

    let typeIcon = <></>;

    switch (currentToast.type) {
      case ToastType.Error:
        typeIcon = (
          <ShieldError16Regular
            color="#dc3545"
            width={20}
            height={20}
            className="bd-placeholder-img rounded me-2"
            aria-hidden={true}
            preserveAspectRatio="xMidYMid slice"
            focusable={false}
          />
        );
        break;

      case ToastType.Success:
        typeIcon = (
          <CheckmarkStarburst16Regular
            color="#198754"
            width={20}
            height={20}
            className="bd-placeholder-img rounded me-2"
            aria-hidden={true}
            preserveAspectRatio="xMidYMid slice"
            focusable={false}
          />
        );
        break;

      default:
        typeIcon = (
          <Comment16Regular
            color="#007aff"
            width={20}
            height={20}
            className="bd-placeholder-img rounded me-2"
            aria-hidden={true}
            preserveAspectRatio="xMidYMid slice"
            focusable={false}
          />
        );
        break;
    }

    return (
      <div
        key={index}
        className="toast fade show"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        ref={e => {
          this.hideToast(e, currentToast.timeout);
        }}>
        <div className="toast-header">
          {typeIcon}

          <strong className="me-auto">{currentToast.name}</strong>
          <small>{this.renderDate()}</small>
          <button
            onClick={e => this.closeButtonOnClick(e)}
            type="button"
            className="btn-close"
            data-bs-dismiss="toast"
            aria-label="Close"></button>
        </div>
        <div className="toast-body">{currentToast.message}</div>
      </div>
    );
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <section className="toast-container">
        {this.toasts.map((toast, index) => {
          return this.renderToast(toast, index);
        })}
      </section>
    );
  }
}
