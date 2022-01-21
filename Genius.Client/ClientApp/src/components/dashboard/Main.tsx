/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';
import { Link } from 'react-router-dom';

interface IMainState {
  isLoading?: boolean;
  systemsList?: any;
}

export class Main extends Component<{}, IMainState> {
  static displayName = Main.name;

  constructor(props) {
    super(props);

    this.state = {
      isLoading: true,
      systemsList: {}
    }
  }

  componentDidMount() {
    this.populateExpertSystemsData();
  }

  static renderSystemsList(systems) {
    if (Object.keys(systems).length < 1) {
      return <p>No systems found</p>;
    }

    return (
      <div className="row">
        {systems.map(system => (
          <div className="col-12 dashboard__section -mb-3 -reveal">
            <div className="dashboard__banner h-100 p-5 bg-light -rounded-2">
              <div>
                <h4>{system.name ?? ''}</h4>
                <p>{system.description ?? ''}</p>
                <Link
                  to={'/dashboard/sys/' + system.guid ?? '#'}
                  className="btn btn-outline-dark btn-mobile -lg-mr-1">
                  Run
                </Link>
                <Link
                  to={'/dashboard/edit/' + system.guid ?? '#'}
                  className="btn btn-dark btn-mobile -lg-mr-1">
                  Edit
                </Link>
              </div>
            </div>
          </div>
        ))}
      </div>
    );
  }

  render() {
    let contents = this.state.isLoading ? (
      <p>
        <em>Loading...</em>
      </p>
    ) : (
      Main.renderSystemsList(this.state.systemsList)
    );

    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Dashboard</h4>
          </div>
        </div>

        {contents}
      </div>
    );
  }

  async populateExpertSystemsData() {
    const response = await fetch('api/expert/system');
    const data = await response.json();

    console.debug('api/expert/system', data);

    this.setState({ systemsList: data, isLoading: false });
  }
}
