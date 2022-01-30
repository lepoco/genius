/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';
import IExpertCondition from '../genius/IExpertCondition';
import ExpertCondition from './../genius/ExpertCondition';
import ExpertProduct from './../genius/ExpertProduct';
import GeniusApi from './../genius/GeniusApi';

interface IFloatingTagsProps {
  systemId?: number;
  name?: string;
  header?: string;
  options?: IExpertCondition[];
  selected?: IExpertCondition[];
  inputValue?: string;
  onUpdate?: CallableFunction;
}

interface IFloatingTagsState {
  systemId?: number;
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
      systemId: props.systemId ?? 0,
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

  async onInputKeyPress(event) {
    if (event.key !== 'Enter') {
      return;
    }

    event.preventDefault();

    if (this.state === undefined) {
      return;
    }

    if (!(event.target instanceof HTMLInputElement)) {
      return;
    }

    if (event.target.value.trim() === '') {
      return;
    }

    console.log(this.state);

    let conditionName = event.target.value.trim();
    let newCondition = new ExpertCondition(0, this.state.systemId, conditionName, '');

    let newConditionId = await GeniusApi.addCondition(newCondition);

    // console.debug('\\FloatingTags\\onInputKeyPress\\newConditionId', newConditionId);

    if(newConditionId < 1) {
      return;
    }

    newCondition.id = newConditionId;

    // TODO: if exists cancel

    event.target.value = '';

    let updatedOptionsList = this.state.options;
    updatedOptionsList.push(newCondition);

    let updatedSelectedList = this.state.selected;
    updatedSelectedList.push(newCondition);

    this.setState({
      options: updatedOptionsList,
      selected: updatedSelectedList,
    });
  }

  onInputChange(event) {
    event.preventDefault();

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
              onKeyPress={this.onInputKeyPress.bind(this)}
              pattern="[a-zA-Z0-9-_ ]+"
              name={this.state.name ?? ''}
              defaultValue={this.state.inputValue}
              onChange={this.onInputChange.bind(this)}
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
