/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';
import IExpertCondition from './../interfaces/IExpertCondition';

interface IFloatingTagsProps {
  name?: string;
  header?: string;
  options?: IExpertCondition[];
  selected?: IExpertCondition[];
  inputValue?: string;
  onUpdate?: CallableFunction;
}

interface IFloatingTagsState {
  name: string;
  header: string;
  options: IExpertCondition[];
  selected: IExpertCondition[];
  inputValue: string;
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
      options: props.options ?? [],
      selected: props.selected ?? [],
    };

    console.log(this.state);
  }

  incrementCounter() {}

  tagAddOnClick(
    event: React.MouseEvent<HTMLSpanElement>,
    condition: IExpertCondition,
  ) {
    let updatedList = this.state.selected;
    updatedList.push(condition);

    this.setState({ selected: updatedList });
  }

  tagRemoveOnClick(
    event: React.MouseEvent<HTMLSpanElement>,
    condition: IExpertCondition,
  ) {
    let updatedList = this.state.selected.filter(
      singleCondition => singleCondition.id !== condition.id,
    );

    this.setState({ selected: updatedList });
  }

  generateSelectedTags() {
    return (
      <div className="floating-tags__list">
        {this.state.selected.map((singleOption, i) => {
          return (
            <span
              onClick={event => this.tagRemoveOnClick(event, singleOption)}
              className="tag -blue">
              {singleOption.name ?? '__UNKNOWN_NAME'}
              <div className="tag__action">
                <span data-role="remove"></span>
              </div>
            </span>
          );
        })}
      </div>
    );
  }

  generateAvailableTags() {
    return (
      <div className="floating-tags__list">
        {this.state.options.map((singleOption, i) => {
          let isSelected = false;

          this.state.selected.forEach(element => {
            if (element.id === singleOption.id) {
              isSelected = true;
            }
          });

          if (isSelected) {
            return false;
          }

          return (
            <span
              onClick={event => this.tagAddOnClick(event, singleOption)}
              className="tag -green">
              {singleOption.name ?? '__UNKNOWN_NAME'}
              <div className="tag__action">
                <span data-role="add"></span>
              </div>
            </span>
          );
        })}
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
      if (!target.value.match('/^' + target.pattern + '$/')) {
        // TODO: Negate html pattern
        target.value = target.value.replace(/[^a-zA-Z0-9-_ ]/g, '');
      }
    }

    // this.setState({
    //   [name]: value,
    // });
  }

  render() {
    const selectedTagsList = this.generateSelectedTags();
    const availableTagsList = this.generateAvailableTags();

    return (
      <div>
        <div className="floating-tags -reveal">
          <label htmlFor={this.state.name ?? ''}>
            {this.state.header ?? ''}
          </label>
          <div className="floating-tags__container">
            {selectedTagsList}

            <input
              type="text"
              pattern="[a-zA-Z0-9-_ ]+"
              name={this.state.name ?? ''}
              defaultValue={this.state.inputValue}
              onChange={this.handleInputChange}
            />
          </div>
        </div>

        <div className="floating-tags -add">
          <div>{availableTagsList}</div>
        </div>
      </div>
    );
  }
}
