/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import RoutedPureComponent from '../../../common/RoutedPureComponent';
import IRouterProps from '../../../interfaces/IRouterProps';
import withRouter from '../../../common/withRouter';
import ExpertSystem from '../../../genius/ExpertSystem';
import GeniusApi from '../../../genius/GeniusApi';

interface ISystemAddState {
  systemName?: string;
  systemDescription?: string;
  systemType?: string;
  systemQuestion?: string;
}

export class SystemAdd extends RoutedPureComponent<ISystemAddState> {
  public static displayName: string = SystemAdd.name;

  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      systemName: '',
      systemDescription: '',
      systemType: 'fuzzy',
      systemQuestion: 'Does your gnome have {condition}?',
    };
  }

  private handleInputChange(event): void {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  private async handleSubmit(event): Promise<void> {
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

    let apiResult = await GeniusApi.addSystem(EXPERT_SYSTEM);

    if (apiResult) {
      this.router.navigate('/dashboard');
    }
  }

  public render(): JSX.Element {
    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Add</h4>
          </div>
          <div className="col-12">
            <form
              id="addSystem"
              method="POST"
              onSubmit={this.handleSubmit.bind(this)}>
              <input type="hidden" name="nonce" readOnly value="addsystem" />

              <h5 className="-font-secondary -fw-700 -pb-1 -reveal">
                New expert system
              </h5>

              <div className="floating-input -reveal">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Name"
                  name="systemName"
                  value={this.state.systemName}
                  onChange={this.handleInputChange.bind(this)}
                />
                <label htmlFor="systemName">Name</label>
              </div>

              <div className="floating-input -reveal">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Description"
                  name="systemDescription"
                  value={this.state.systemDescription}
                  onChange={this.handleInputChange.bind(this)}
                />
                <label htmlFor="systemDescription">Description</label>
              </div>

              <div className="floating-input -reveal">
                <select
                  id="systemType"
                  data-selected="fuzzy"
                  className="floating-input__field"
                  name="systemType"
                  placeholder="Type"
                  value={this.state.systemQuestion}
                  onChange={this.handleInputChange.bind(this)}>
                  <option value="relational">Relation based</option>
                  <option disabled value="fuzzy">
                    Fuzzy set method (weight)
                  </option>
                </select>
                <label htmlFor="systemType">Type</label>
              </div>

              <div className="-mb-2">
                <hr />
              </div>

              <div className="floating-input -reveal">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Primary question"
                  name="systemQuestion"
                  value={this.state.systemQuestion}
                  onChange={this.handleInputChange.bind(this)}
                />
                <label htmlFor="systemQuestion">Primary question</label>
              </div>

              <div className="-mb-3 -reveal">
                <i>
                  Main question is displayed during system running. Presents the
                  name of the condition inside the bracket
                  &#123;condition&#125;.
                </i>
                <br />
                <i>
                  If the question has no bracket pattern, condition will be
                  displayed below.
                </i>
              </div>

              <div className="-reveal -pb-1">
                <button
                  type="submit"
                  className="btn btn-dark btn-mobile -lg-mr-1">
                  Create
                </button>
                <Link
                  to="/dashboard"
                  className="btn btn-outline-dark btn-mobile">
                  Cancel
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    );
  }
}

export default withRouter(SystemAdd);
