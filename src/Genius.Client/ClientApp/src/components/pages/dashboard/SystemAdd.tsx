/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { Link } from 'react-router-dom';
import { Genius, ExpertSystem } from '../../../genius/Genius';

/**
 * Represents the variables contained in the component state.
 */
interface ISystemAddState {
  systemName: string;
  systemDescription: string;
  systemType: string;
  systemQuestion: string;
}

export class SystemAdd extends ORouter.PureComponent<ISystemAddState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = SystemAdd.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      systemName: '',
      systemDescription: '',
      systemType: 'fuzzy',
      systemQuestion: 'Does your gnome have {condition}?',
    };

    this.formOnSubmit = this.formOnSubmit.bind(this);
    this.inputOnChange = this.inputOnChange.bind(this);
    this.buttonImportOnClick = this.buttonImportOnClick.bind(this);
  }

  private async inputOnChange(
    event: React.ChangeEvent<HTMLInputElement> | React.ChangeEvent<HTMLSelectElement>,
  ): Promise<boolean> {
    const target: HTMLInputElement | HTMLSelectElement = event.target;
    const name: string = target.name;
    let value: string | boolean = target.value;

    if (target instanceof HTMLInputElement && target.type === 'checkbox') {
      value = target.checked;
    }

    // @ts-ignore
    this.setState({
      [name]: value,
    });

    return true;
  }

  private async buttonImportOnClick(event: React.MouseEvent<HTMLButtonElement, MouseEvent>): Promise<boolean> {
    event.preventDefault();

    return false;
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    const EXPERT_SYSTEM = new ExpertSystem(
      0,
      '',
      '',
      this.state.systemName,
      this.state.systemDescription,
      this.state.systemType,
      this.state.systemQuestion,
    );

    let apiResult = await Genius.Api.addSystem(EXPERT_SYSTEM);

    if (apiResult) {
      this.router.navigate('/dashboard');
    }

    return true;
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3">Add</h4>
          </div>
          <div className="col-12">
            <form id="addSystem" method="POST" onSubmit={e => this.formOnSubmit(e)}>
              <input type="hidden" name="nonce" readOnly value="addsystem" />

              <h5 className="-font-secondary -fw-700 -pb-1">New expert system</h5>

              <div className="floating-input">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Name"
                  name="systemName"
                  value={this.state.systemName}
                  onChange={e => this.inputOnChange(e)}
                />
                <label htmlFor="systemName">Name</label>
              </div>

              <div className="floating-input">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Description"
                  name="systemDescription"
                  value={this.state.systemDescription}
                  onChange={e => this.inputOnChange(e)}
                />
                <label htmlFor="systemDescription">Description</label>
              </div>

              <div className="floating-input">
                <select
                  id="systemType"
                  data-selected="fuzzy"
                  className="floating-input__field"
                  name="systemType"
                  placeholder="Type"
                  value={this.state.systemQuestion}
                  onChange={e => this.inputOnChange(e)}>
                  <option value="relational">Relation based</option>
                  <option disabled value="relational_non_confident">
                    Relation with confidence score
                  </option>
                  <option disabled value="fuzzy">
                    Fuzzy set method (weight)
                  </option>
                  <option disabled value="fuzy_multi_value">
                    Fuzzy set method and multi value
                  </option>
                </select>
                <label htmlFor="systemType">Type</label>
              </div>

              <div className="-mb-2">
                <hr />
              </div>

              <div className="floating-input">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Primary question"
                  name="systemQuestion"
                  value={this.state.systemQuestion}
                  onChange={e => this.inputOnChange(e)}
                />
                <label htmlFor="systemQuestion">Primary question</label>
              </div>

              <div className="-mb-3">
                <i>
                  Main question is displayed during system running. Presents the name of
                  the condition inside the bracket &#123;condition&#125;.
                </i>
                <br />
                <i>
                  If the question has no bracket pattern, condition will be displayed
                  below.
                </i>
              </div>

              <div className="-reveal -pb-1">
                <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
                  Create
                </button>
                <Link to="/dashboard" className="btn btn-outline-dark btn-mobile -lg-mr-1">
                  Cancel
                </Link>
                <button type="button" className="btn btn-outline-dark btn-mobile" onClick={e => this.buttonImportOnClick(e)}>
                  Import from file
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    );
  }
}

export default ORouter.bind(SystemAdd);
