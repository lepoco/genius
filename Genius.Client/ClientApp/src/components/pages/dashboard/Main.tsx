/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import RoutedPureComponent from '../../../common/RoutedPureComponent';
import IRouterProps from '../../../interfaces/IRouterProps';
import { Link } from 'react-router-dom';
import GeniusApi from '../../../genius/GeniusApi';
import IExpertSystem from '../../../genius/interfaces/IExpertSystem';
import Loader from '../../common/Loader';
import withRouter from '../../../common/withRouter';

interface IMainState {
  isLoading: boolean;
  systemsList: IExpertSystem[];
}

export class Main extends RoutedPureComponent<IMainState> {
  public static displayName: string = Main.name;

  public constructor(props: IRouterProps) {
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
    let systems = await GeniusApi.getAllSystems();

    for (let index = 0; index < systems.length; index++) {
      const systemAbout = await GeniusApi.getSystemAbout(systems[index].id);

      systems[index].productsCount = systemAbout.products;
      systems[index].conditionsCount = systemAbout.conditions;
      systems[index].relationsCount = systemAbout.relations;
    }

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
            key={system.id ?? 0}
            className="col-12 dashboard__section -mb-3">
            <div className="dashboard__banner h-100 p-5 bg-light -rounded-2">
              <div style={{ width: '100%' }}>
                <h4>{system.name ?? ''}</h4>
                <p>{system.description ?? ''}</p>
                <div>
                  <div className="row">
                    <div className="col-12 col-lg-4">
                      <strong>Possible results:</strong>
                      <p>{system.productsCount ?? 0}</p>
                    </div>
                    <div className="col-12 col-lg-4">
                      <strong>Number of connections:</strong>
                      <p>{system.relationsCount ?? 0}</p>
                    </div>
                    <div className="col-12 col-lg-4">
                      <strong>Conditions available:</strong>
                      <p>{system.conditionsCount ?? 0}</p>
                    </div>
                  </div>
                </div>
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

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    let contents = this.state.isLoading ? (
      <Loader center={false} />
    ) : (
      Main.renderSystemsList(this.state.systemsList)
    );

    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3">Dashboard</h4>
          </div>
        </div>

        {contents}
      </div>
    );
  }
}

export default withRouter(Main);
