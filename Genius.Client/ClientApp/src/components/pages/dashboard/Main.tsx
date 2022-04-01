/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { Link } from 'react-router-dom';
import { Genius, IExpertSystem } from '../../../genius/Genius';
import Loader from '../../common/Loader';

/**
 * Represents the variables contained in the Component state.
 */
interface IMainState {
  isLoading: boolean;
  systemsList: IExpertSystem[];
}

/**
 * Dashboard - Main page Component.
 */
export class Main extends ORouter.PureComponent<IMainState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Main.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      isLoading: true,
      systemsList: [],
    };
  }

  /**
   * Called immediately after a component is mounted. Setting state here will trigger re-rendering.
   */
  public async componentDidMount(): Promise<boolean> {
    return await this.populateData();
  }

  /**
   * Asynchronously gets data from the server.
   */
  private async populateData(): Promise<boolean> {
    let systems = await Genius.Api.getAllSystems();

    for (let index = 0; index < systems.length; index++) {
      const systemAbout = await Genius.Api.getSystemAbout(systems[index].id);

      systems[index].productsCount = systemAbout.products;
      systems[index].conditionsCount = systemAbout.conditions;
      systems[index].relationsCount = systemAbout.relations;
    }

    this.setState({ systemsList: systems, isLoading: false });

    return true;
  }

  /**
   * Renders additional view elements - a list of expert systems.
   */
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
          <div key={system.id ?? 0} className="col-12 dashboard__section -mb-3">
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

export default ORouter.bind(Main);
