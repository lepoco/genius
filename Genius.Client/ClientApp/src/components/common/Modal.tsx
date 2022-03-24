/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';

interface IModalProps {
  name: string;
  title: string;
}

interface IModalState {
  name: string;
  title: string;
}

export default class Modal extends Component<IModalProps, IModalState> {
  static displayName: string = Modal.name;
  private visible: boolean = false;
  private element: HTMLDivElement | null = null;

  public constructor(props: IModalProps) {
    super(props);

    this.state = {
      name: props.name ?? 'default-modal',
      title: props.title ?? 'Default title',
    };

    this.closeOnClick = this.closeOnClick.bind(this);
  }

  public toggle(): void {}

  public show(): void {
    if (this.element == null) return;
    document.body.classList.add('modal-open');

    this.element.classList.add('show');
    this.element.style.display = 'block';

    this.visible = true;
  }

  public hide(): void {
    document.body.classList.remove('modal-open');

    if (this.element == null) return;

    this.element.classList.remove('show');
    this.element.style.display = 'none';

    this.visible = false;
  }

  private closeOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): void {
    this.hide();
  }

  public render(): JSX.Element {
    return (
      <div
        className="modal fade"
        id={this.state.name}
        ref={element => {
          this.element = element;
        }}
        tabIndex={-1}
        aria-labelledby={this.state.name + 'Label'}
        aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id={this.state.name + 'Label'}>
                {this.state.title}
              </h5>
              <button
                onClick={e => this.closeOnClick(e)}
                type="button"
                className="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div className="modal-body">{this.props.children}</div>
          </div>
        </div>
      </div>
    );
  }
}
