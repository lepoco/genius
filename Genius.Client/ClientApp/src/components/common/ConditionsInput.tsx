/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';
import IExpertCondition from '../../genius/interfaces/IExpertCondition';
import ExpertCondition from '../../genius/ExpertCondition';
import GeniusApi from '../../genius/GeniusApi';

interface TagsUpdated<T = IExpertCondition[]> {
  (selected: T, available: T): void;
}

interface IConditionsInputProps {
  systemId?: number;
  inputName?: string;
  conditionsSelected?: IExpertCondition[];
  onUpdate?: TagsUpdated;
}

interface IConditionsInputState {
  contentLoaded: boolean;
  systemId: number;
  inputName: string;
  conditionsAvailable: IExpertCondition[];
  conditionsSelected: IExpertCondition[];
}

/**
 * Allows you to add and select conditions from a given expert system.
 */
export class ConditionsInput extends Component<
  IConditionsInputProps,
  IConditionsInputState
> {
  private onUpdate?: TagsUpdated<IExpertCondition[]>;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: IConditionsInputProps) {
    super(props);
    this.state = {
      contentLoaded: false,
      inputName: props.inputName ?? 'Conditions',
      systemId: props.systemId ?? 0,
      conditionsSelected: props.conditionsSelected ?? [],
      conditionsAvailable: [],
    };

    if (props.onUpdate !== undefined) this.onUpdate = props.onUpdate;

    this.inputOnChange = this.inputOnChange.bind(this);
    this.inputOnKeyPress = this.inputOnKeyPress.bind(this);
    this.buttonAddOnClick = this.buttonAddOnClick.bind(this);
    this.buttonRemoveOnClick = this.buttonRemoveOnClick.bind(this);
  }

  /**
   * Resets selected conditions.
   */
  public clear(): void {
    this.setState({ conditionsSelected: [] });
  }

  /**
   * Called immediately after a component is mounted. Setting state here will trigger re-rendering.
   */
  public async componentDidMount(): Promise<boolean> {
    return await this.populateData();
  }

  /**
   * Sends a notification to the parent object.
   */
  public invokeOnUpdate(): boolean {
    if (this.onUpdate === undefined) {
      return false;
    }

    this.onUpdate(
      this.state.conditionsSelected ?? [],
      this.state.conditionsAvailable ?? [],
    );

    return true;
  }

  /**
   * Asynchronously gets data from the server.
   */
  private async populateData(): Promise<boolean> {
    const systemConditions = await GeniusApi.getSystemConditions(this.state.systemId);

    this.setState({
      contentLoaded: true,
      conditionsAvailable: systemConditions,
    });

    return true;
  }

  private async buttonAddOnClick(
    event: React.MouseEvent<HTMLSpanElement>,
    condition: IExpertCondition,
  ): Promise<boolean> {
    event.preventDefault();

    console.debug('\\ConditionsInput\\buttonAddOnClick\\condition', condition);

    let updatedList = this.state.conditionsSelected;
    updatedList.push(condition);

    this.setState({ conditionsSelected: updatedList });
    this.invokeOnUpdate();

    return true;
  }

  private async buttonRemoveOnClick(
    event: React.MouseEvent<HTMLSpanElement>,
    condition: IExpertCondition,
  ): Promise<boolean> {
    event.preventDefault();

    console.debug('\\ConditionsInput\\buttonRemoveOnClick\\condition', condition);

    const updatedList = this.state.conditionsSelected.filter(
      singleCondition => singleCondition.id !== condition.id,
    );

    this.setState({ conditionsSelected: updatedList });
    this.invokeOnUpdate();

    return true;
  }

  private async inputOnKeyPress(
    event: React.KeyboardEvent<HTMLInputElement>,
  ): Promise<boolean> {
    if (event.key !== 'Enter') {
      return false;
    }

    event.preventDefault();

    if (this.state === undefined) {
      return false;
    }

    if (!(event.target instanceof HTMLInputElement)) {
      return false;
    }

    if (event.target.value.trim() === '') {
      return false;
    }

    const conditionName = event.target.value.trim();

    let existingCondition: IExpertCondition | null = null;

    // If condition already exist and have same name
    this.state.conditionsAvailable.forEach(con => {
      if (con.name === undefined) {
        return;
      }

      if (con.name.toLowerCase() === conditionName.toLowerCase()) {
        existingCondition = con;
      }
    });

    if (existingCondition !== null) {
      let updatedList = this.state.conditionsSelected;
      updatedList.push(existingCondition);

      this.setState({ conditionsSelected: updatedList });
      this.invokeOnUpdate();

      event.target.value = '';

      console.debug(
        '\\ConditionsInput\\inputOnKeyPress',
        'This condition already exists.',
      );

      return true;
    }

    let newCondition = new ExpertCondition(0, this.state.systemId, conditionName, '');

    const newConditionId = await GeniusApi.addCondition(newCondition);

    // console.debug('\\FloatingTags\\inputOnKeyPress\\newConditionId', newConditionId);

    if (newConditionId < 1) {
      return false;
    }

    newCondition.id = newConditionId;

    // TODO: if exists cancel

    event.target.value = '';

    let updatedOptionsList = this.state.conditionsAvailable;
    updatedOptionsList.push(newCondition);

    let updatedSelectedList = this.state.conditionsSelected;
    updatedSelectedList.push(newCondition);

    this.setState({
      conditionsAvailable: updatedOptionsList,
      conditionsSelected: updatedSelectedList,
    });

    this.invokeOnUpdate();

    return true;
  }

  private async inputOnChange(
    event: React.ChangeEvent<HTMLInputElement>,
  ): Promise<boolean> {
    event.preventDefault();

    const target = event.target;
    const value = target.value;
    const name = target.name;

    if (!(target instanceof HTMLInputElement)) {
      return false;
    }

    if (target.pattern != null) {
      if (!target.value.match('/^' + target.pattern + '$/')) {
        // TODO: Negate html pattern
        target.value = target.value.replace(/[^a-zA-Z0-9-_ ]/g, '');
      }
    }

    return true;
  }

  private renderSelectedConditions(): JSX.Element {
    return (
      <div className="floating-tags__list">
        {this.state.conditionsSelected.map((singleOption, i) => {
          return (
            <span
              key={singleOption.id ?? 0}
              onClick={e => this.buttonRemoveOnClick(e, singleOption)}
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

  private renderAvailableConditions(): JSX.Element {
    return (
      <div className="floating-tags__list -add">
        {this.state.conditionsAvailable.map((singleOption, i) => {
          let isSelected = false;

          this.state.conditionsSelected.forEach(element => {
            if (element.id === singleOption.id) {
              isSelected = true;
            }
          });

          if (isSelected) {
            return false;
          }

          return (
            <span
              key={singleOption.id ?? 0}
              onClick={e => this.buttonAddOnClick(e, singleOption)}
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

  /**
   * The main method responsible for refreshing the view.
   */
  public render(): JSX.Element {
    return (
      <div>
        {!this.state.contentLoaded ? (
          <p>Loading...</p>
        ) : (
          <>
            <div className="floating-tags -reveal">
              <label htmlFor={this.state.inputName.toLowerCase()}>
                {this.state.inputName}
              </label>
              <div className="floating-tags__container">
                {this.renderSelectedConditions()}

                <input
                  type="text"
                  onKeyPress={e => this.inputOnKeyPress(e)}
                  pattern="[a-zA-Z0-9-_ ]+"
                  name={this.state.inputName.toLowerCase()}
                  defaultValue={''}
                  onChange={e => this.inputOnChange(e)}
                />
              </div>
            </div>

            <>{this.renderAvailableConditions()}</>
          </>
        )}
      </div>
    );
  }
}
