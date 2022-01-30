/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';
import { Link } from 'react-router-dom';
import { FloatingTags } from '../FloatingTags';
import withRouter from './../../common/withRouter';
import IRouterProps from './../../interfaces/IRouterProps';
import IRouter from './../../interfaces/IRouter';
import IExpertPageState from '../../genius/IExpertPageState';
import ExpertCondition from './../../genius/ExpertCondition';
import GeniusApi from '../../genius/GeniusApi';

class SystemEdit extends Component<IRouterProps, IExpertPageState> {
  static displayName = SystemEdit.name;

  router: IRouter;

  constructor(props: IRouterProps) {
    super(props);

    this.router = props.router;

    this.state = {
      systemLoaded: false,
      systemId: 0,
      systemGuid: '',
      systemVersion: '',
      systemName: '',
      systemDescription: '',
      systemType: '',
      systemQuestion: '',
      systemCreatedAt: '',
      systemUpdatedAt: '',
      systemConditions: [],
      systemProducts: [],
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount() {
    this.populateExpertSystemData();
  }

  async populateExpertSystemData() {
    const system = await GeniusApi.getSystemByGuid(
      this.router.params.guid ?? '',
      true,
      true,
      true,
    );

    this.setState({
      systemLoaded: true,

      systemId: system.systemId ?? 0,
      systemVersion: system.systemVersion ?? '',
      systemName: system.systemName ?? '',
      systemDescription: system.systemDescription ?? '',
      systemGuid: system.systemGuid ?? '',
      systemQuestion: system.systemQuestion ?? '',
      systemType: system.systemType ?? '',
      systemCreatedAt: system.systemCreatedAt ?? '',
      systemUpdatedAt: system.systemUpdatedAt ?? '',
      systemConditions: system.systemConditions ?? [],
      systemProducts: system.systemProducts ?? [],
      systemRelations: system.systemRelations ?? [],
    });
  }

  handleInputChange(event) {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  async handleSubmit(event) {
    event.preventDefault();

    console.debug(event);

    // const formData = new FormData();

    // formData.append('name', this.state.systemName ?? '');
    // formData.append('description', this.state.systemDescription ?? '');
    // formData.append('question', this.state.systemQuestion ?? '');
    // formData.append('type', this.state.systemType ?? '');

    // await fetch('api/expert/system', {
    //   method: 'UPDATE',
    //   body: formData,
    // })
    //   .then(response => response.text())
    //   .then(data => {

    //     //this.setState({ text: data, loading: false });
    //   });
  }

  renderSystemView(state: IExpertPageState) {
    if ((state.systemId ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <div className="row">
        <div className="col-12">
          <div className="-reveal">
            <span>System name</span>
            <h5 className="-font-secondary -fw-700">
              {state.systemName ?? ''}
            </h5>
          </div>
          <div className="-reveal">
            <span>Creation date:</span>
            <h5 className="-font-secondary -fw-700 -pb-3">
              {state.systemCreatedAt ?? '__unknown'}
            </h5>
          </div>

          <div className="-mb-3 -reveal">
            <button className="btn btn-dark btn-mobile -lg-mr-1 -btn-export">
              Export
            </button>
            <button className="btn btn-outline-dark btn-mobile -btn-import -lg-mr-1">
              Import
            </button>
            <Link to={'/dashboard/delete/' + state.systemGuid ?? '#'}>
              Remove the expert system
            </Link>
          </div>
        </div>

        <div className="col-12 -mb-2">
          <hr />
        </div>

        <div className="col-12">
          <form id="addProduct" method="POST" onSubmit={this.handleSubmit}>
            <h5 className="-font-secondary -fw-700 -pb-1 -reveal">
              New product
            </h5>

            <div className="-reveal">
              <p>
                Product is the final result of the application's operation. If
                the purpose of the system is to select the best garden gnomes,
                the product can be - <i>"Red Gnome by iGnome INC"</i>.
              </p>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Name"
                name="product_name"
              />
              <label htmlFor="product_name">Name</label>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Description"
                name="product_description"
              />
              <label htmlFor="product_description">Description</label>
            </div>

            <p>
              <small>
                <i>
                  Tip: At the bottom you can see all conditions already
                  available in the database, you can add a new condition by
                  typing its name and pressing enter.
                </i>
              </small>
            </p>

            <FloatingTags
              name="product_conditions_confirming"
              header="Conditions (confirming)"
              systemId={this.state.systemId ?? 0}
              options={this.state.systemConditions}
              selected={[]}
            />

            {/* <FloatingTags
              name="product_conditions_negating"
              header="Conditions (negating)"
              systemId={this.state.systemId ?? 0}
              options={this.state.systemConditions}
              selected={[]}
            /> */}

            <div className="-reveal -pb-2">
              <button
                type="submit"
                className="btn btn-dark btn-mobile -lg-mr-1">
                Add
              </button>
            </div>
          </form>
        </div>

        <div className="col-12">
          <hr />
        </div>

        <div className="col-12">
          <h5 className="-font-secondary -fw-700 -pb-1 -reveal">Products</h5>
        </div>
      </div>
    );
  }

  render() {
    let contents = !this.state.systemLoaded ? (
      <p>
        <em>Loading...</em>
      </p>
    ) : (
      this.renderSystemView(this.state)
    );

    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Edit</h4>
          </div>
        </div>

        {contents}
      </div>
    );
  }
}

export default withRouter(SystemEdit);
