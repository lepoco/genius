/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';

interface IFloatingTagsProps {
  name?: string;
  header?: string;
  options?: object;
  selected?: number[];
  inputValue?: string;
}

interface IFloatingTagsState {
  name?: string;
  header?: string;
  options?: object;
  selected?: number[];
  inputValue?: string;
}

export class FloatingTags extends Component<
  IFloatingTagsProps,
  IFloatingTagsState
> {
  static displayName = FloatingTags.name;

  constructor(props: IFloatingTagsProps) {
    super(props);
    this.state = {
      name: props.name ?? '',
      header: props.header ?? '',
      inputValue: props.inputValue ?? '',
      options: props.options ?? {},
      selected: props.selected ?? [],
    };

    console.log(this.state);
  }

  incrementCounter() {}

  generateTagsList() {
    return (
      <div className="floating-tags__list">
        <span className="tag">
          First condition
          <div className="close">
            <span data-role="remove"></span>
          </div>
        </span>
        <span className="tag">
          Second condition
          <div className="close">
            <span data-role="remove"></span>
          </div>
        </span>
      </div>
    );
  }

  handleInputChange(event) {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    if (!(target instanceof HTMLInputElement)) {
      return;
    }

    if (target.pattern != null) {
      if(!target.value.match("/^"+target.pattern+"$/")) {
        // TODO: Negate html pattern
        target.value = target.value.replace(/[^a-zA-Z0-9-_ ]/g, '');
      }
    }

    // this.setState({
    //   [name]: value,
    // });
  }

  render() {
    const tagsList = this.generateTagsList();

    return (
      <div className="floating-tags -reveal">
        <label htmlFor={this.state.name ?? ''}>{this.state.header ?? ''}</label>
        <div className="floating-tags__container">
          {tagsList}

          <input
            type="text"
            pattern="[a-zA-Z0-9-_ ]+"
            name={this.state.name ?? ''}
            defaultValue={this.state.inputValue}
            onChange={this.handleInputChange}
          />
        </div>
      </div>
    );
  }
}
