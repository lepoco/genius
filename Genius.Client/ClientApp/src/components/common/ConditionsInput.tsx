/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';
import { Genius, ExpertCondition, IExpertCondition } from '../../genius/Genius';

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
  // conditionsAvailable: IExpertCondition[];
  // conditionsSelected: IExpertCondition[];
}

/**
 * Allows you to add and select conditions from a given expert system.
 */
export class ConditionsInput extends Component<
  IConditionsInputProps,
  IConditionsInputState
> {
  private onUpdate?: TagsUpdated<IExpertCondition[]>;

  private conditionsAvailable: IExpertCondition[];
  private conditionsSelected: IExpertCondition[];

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
      // conditionsSelected: props.conditionsSelected ?? [],
      // conditionsAvailable: [],
    };

    this.conditionsAvailable = [];
    this.conditionsSelected = props.conditionsSelected ?? [];

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
    this.conditionsSelected = [];
    this.invokeOnUpdate();
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

    this.forceUpdate();

    this.onUpdate(this.conditionsSelected ?? [], this.conditionsAvailable ?? []);

    return true;
  }

  /**
   * Asynchronously gets data from the server.
   */
  private async populateData(): Promise<boolean> {
    const systemConditions = await Genius.Api.getSystemConditions(this.state.systemId);

    this.conditionsAvailable = systemConditions;

    this.setState({
      contentLoaded: true,
    });

    return true;
  }

  private async addCondition(conditionName: string): Promise<boolean> {
    // console.debug('\\ConditionsInput\\addCondition', conditionName);

    let existingCondition: IExpertCondition | null = null;

    // If condition already exist and have same name
    this.conditionsAvailable.forEach(con => {
      if (con.name === undefined) {
        return;
      }

      if (con.name.toLowerCase() === conditionName.toLowerCase()) {
        existingCondition = con;
      }
    });

    if (existingCondition !== null) {
      let updatedList = this.conditionsSelected;
      updatedList.push(existingCondition);

      this.conditionsSelected = updatedList;

      // console.debug('\\ConditionsInput\\addCondition', 'This condition already exists.');

      return true;
    }

    let newCondition = new ExpertCondition(0, this.state.systemId, conditionName, '');

    const newConditionId = await Genius.Api.addCondition(newCondition);

    // console.debug('\\FloatingTags\\inputOnKeyPress\\newConditionId', newConditionId);

    if (newConditionId < 1) {
      return false;
    }

    newCondition.id = newConditionId;

    let updatedOptionsList = this.conditionsAvailable;
    updatedOptionsList.push(newCondition);

    let updatedSelectedList = this.conditionsSelected;
    updatedSelectedList.push(newCondition);

    this.conditionsAvailable = updatedOptionsList;
    this.conditionsSelected = updatedSelectedList;

    return true;
  }

  private async buttonAddOnClick(
    event: React.MouseEvent<HTMLSpanElement>,
    condition: IExpertCondition,
  ): Promise<boolean> {
    event.preventDefault();

    // console.debug('\\ConditionsInput\\buttonAddOnClick\\condition', condition);

    let updatedList = this.conditionsSelected;
    updatedList.push(condition);

    this.conditionsSelected = updatedList;

    this.invokeOnUpdate();

    return true;
  }

  private async buttonRemoveOnClick(
    event: React.MouseEvent<HTMLSpanElement>,
    condition: IExpertCondition,
  ): Promise<boolean> {
    event.preventDefault();

    // console.debug('\\ConditionsInput\\buttonRemoveOnClick\\condition', condition);

    const updatedList = this.conditionsSelected.filter(
      singleCondition => singleCondition.id !== condition.id,
    );

    this.conditionsSelected = updatedList;

    //this.setState({ conditionsSelected: updatedList });
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

    const inputValue = event.target.value.trim();
    const conditions = inputValue.split(',');

    if (inputValue === '') {
      return false;
    }

    for (const condition of conditions) {
      await this.addCondition(condition.trim());
    }

    event.target.value = '';

    this.invokeOnUpdate();

    return true;
  }

  private async inputOnChange(
    event: React.ChangeEvent<HTMLInputElement>,
  ): Promise<boolean> {
    event.preventDefault();

    if (!(event.target instanceof HTMLInputElement)) {
      return false;
    }

    const pattern = /^([\s.]?[a-zA-Z]+)+$/;
    const inputValue = event.target.value;

    if (!inputValue.match('/^' + pattern + '$/')) {
      // TODO: Negate html pattern
      event.target.value = inputValue.replace(/[^a-zA-Z0-9-_, ]/g, '');
    }

    return true;
  }

  private renderSelectedConditions(): JSX.Element {
    return (
      <div className="floating-tags__list">
        {this.conditionsSelected.map((singleOption, i) => {
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
        {this.conditionsAvailable.map((singleOption, i) => {
          let isSelected = false;

          this.conditionsSelected.forEach(element => {
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
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <div>
        {!this.state.contentLoaded ? (
          <p>Loading...</p>
        ) : (
          <>
            <div className="floating-tags">
              <label htmlFor={this.state.inputName.toLowerCase()}>
                {this.state.inputName}
              </label>
              <div className="floating-tags__container">
                {this.renderSelectedConditions()}

                <input
                  type="text"
                  onKeyPress={e => this.inputOnKeyPress(e)}
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
