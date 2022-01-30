/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import RoutedComponent from '../../common/RoutedComponent';
import withRouter from './../../common/withRouter';
import IRouterProps from './../../interfaces/IRouterProps';
import IExpertState from './../../genius/IExpertState';

class System extends RoutedComponent<IExpertState> {
  static displayName = System.name;

  conditionalQuestion: boolean = false;

  constructor(props: IRouterProps) {
    super(props);

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
      systemConditions: {},
      systemProducts: {},
    };
  }

  componentDidMount() {
    this.populateExpertSystemData();
  }

  async populateExpertSystemData() {
    let guid = this.router.params.guid;
    const response = await fetch('api/expert/system/' + guid);
    const data = await response.json();

    this.setState({
      systemId: data.id ?? 0,
      systemVersion: data.version ?? '',
      systemName: data.name ?? '',
      systemDescription: data.description ?? '',
      systemGuid: data.guid ?? '',
      systemQuestion: data.question ?? '',
      systemType: data.type ?? '',
      systemCreatedAt: data.createdAt ?? '',
      systemUpdatedAt: data.updatedAt ?? '',
      systemLoaded: true,
    });
  }

  async handleSubmit(event) {
    event.preventDefault();

    const formData = new FormData();

    await fetch('api/expert/system/response', {
      method: 'POST',
      body: formData,
    })
      .then(response => response.text())
      .then(data => {
        console.debug('Respone', data);

        //this.setState({ text: data, loading: false });
      });
  }

  renderSystemView() {
    if ((this.state.systemId ?? 0) < 1) {
      return <p>System not found</p>;
    }

    return (
      <div className="row">
        <div className="col-12 -mb-3">
          <h4 className="-font-secondary -fw-700 -reveal">
            {this.state.systemName ?? ''}
          </h4>
          <p className="-reveal">{this.state.systemDescription ?? ''}</p>
        </div>

        <div className="col-12 -reveal">
          {!this.conditionalQuestion ? (
            <h4 className="-font-secondary -fw-700 -pb-3">
              <span className="--current_condition -pattern">
                question__pattern
              </span>
            </h4>
          ) : (
            <div>
              <p>
                <strong>{this.state.systemQuestion ?? ''}</strong>
              </p>
              <h4 className="-font-secondary -fw-700 -pb-3">
                <span className="--current_condition">
                  non conditional question
                </span>
              </h4>
            </div>
          )}
        </div>

        <div className="col-12 -reveal">
          <form id="answer" method="POST" onSubmit={this.handleSubmit}>
            <button
              type="submit"
              id="submit_yes"
              name="submit_yes"
              value="yes"
              className="-yes btn btn-dark btn-mobile -lg-mr-1">
              Yes
            </button>
            <button
              type="submit"
              id="submit_no"
              name="submit_no"
              value="no"
              className="-no btn btn-outline-dark btn-mobile -lg-mr-1">
              No
            </button>
            <button
              type="submit"
              id="submit_dontknow"
              name="submit_dontknow"
              value="dontknow"
              className="-dontknow btn btn-outline-dark btn-mobile">
              I do not know
            </button>
          </form>
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
      this.renderSystemView()
    );

    return <div className="dashboard container pt-5 pb-5">{contents}</div>;
  }
}

export default withRouter(System);
