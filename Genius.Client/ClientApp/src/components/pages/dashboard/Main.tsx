/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';
import { Link } from 'react-router-dom';
import GeniusApi from '../../../genius/GeniusApi';
import IExpertSystem from '../../../genius/interfaces/IExpertSystem';
import Loader from '../../common/Loader';

interface IMainState {
  isLoading: boolean;
  systemsList: IExpertSystem[];
}

export class Main extends Component<{}, IMainState> {
  public static displayName: string = Main.name;

  public constructor(props) {
    super(props);

    this.state = {
      isLoading: true,
      systemsList: [],
    };
  }

  public componentDidMount(): void {
    this.populateExpertSystemsData();
  }

  private async populateExpertSystemsData(): Promise<void> {
    const systems = await GeniusApi.getAllSystems();

    this.setState({ systemsList: systems, isLoading: false });
  }

  private static renderSystemsList(systems: IExpertSystem[]): JSX.Element {
    if (Object.keys(systems).length < 1) {
      return (
        <div>
          <p>No systems found</p>
          <Link to={'/dashboard/add'}>Add new expert system</Link>
        </div>
      );
    }

    return (
      <div className="row">
        {systems.map(system => (
          <div
            key={system.systemId ?? 0}
            className="col-12 dashboard__section -mb-3 -reveal">
            <div className="dashboard__banner h-100 p-5 bg-light -rounded-2">
              <div>
                <h4>{system.systemName ?? ''}</h4>
                <p>{system.systemDescription ?? ''}</p>
                <Link
                  to={'/dashboard/sys/' + system.systemGuid ?? '#'}
                  className="btn btn-outline-dark btn-mobile -lg-mr-1">
                  Run
                </Link>
                <Link
                  to={'/dashboard/edit/' + system.systemGuid ?? '#'}
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

  public render(): JSX.Element {
    let contents = this.state.isLoading ? (
      <Loader />
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
}
