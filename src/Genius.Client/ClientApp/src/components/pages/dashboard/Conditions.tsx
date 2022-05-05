/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { Link } from 'react-router-dom';
import { Genius, ExpertSystem, IExpertSystem } from '../../../genius/Genius';
import Loader from '../../common/Loader';
import { Edit16Regular /*, Delete16Regular*/ } from '@fluentui/react-icons';

/**
 * Represents the variables contained in the Component state.
 */
interface IConditionsState {
  contentLoaded: boolean;

  selectedSystemGuid: string;

  selectedSystem: IExpertSystem;
}

/**
 * Dashboard - Conditions page Component.
 */
export class Conditions extends ORouter.PureComponent<IConditionsState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Conditions.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      contentLoaded: false,
      selectedSystemGuid: props.router.params?.guid ?? '',
      selectedSystem: new ExpertSystem(),
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
    const system = await Genius.Api.getSystemByGuid(
      this.state.selectedSystemGuid,
      false,
      true,
      true,
    );

    if (system.id < 1) {
      return false;
    }

    // console.debug('\\Conditions\\populateData\\system', system);

    this.setState({
      selectedSystem: system,
      contentLoaded: true,
    });

    return true;
  }

  public renderContent(): JSX.Element {
    return (
      <>
        <div className="table-responsive">
          <table className="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Relations</th>
                <th scope="col"></th> {/* Actions */}
              </tr>
            </thead>
            <tbody>
              {this.state.selectedSystem.conditions.map((condition, index) => {
                const conditionId: number = condition.id;
                const relationsCount: number = this.state.selectedSystem.relations.filter(
                  rel => rel.conditionId === conditionId,
                ).length;

                return (
                  <tr key={index}>
                    <th scope="row">{conditionId ?? '0'}</th>
                    <td className="-title">{condition.name ?? ''}</td>
                    <td className="-ellipsis">
                      {(condition.description ?? '') === '' ? (
                        '---'
                      ) : (
                        <i>{condition.description}</i>
                      )}
                    </td>
                    <td>{relationsCount}</td>
                    <td>
                      <Link
                        to={
                          '/dashboard/condition/' +
                          this.state.selectedSystemGuid +
                          '/' +
                          conditionId
                        }
                        className="btn btn-icon btn-outline-dark -lg-mr-05">
                        <Edit16Regular />
                      </Link>
                      {/* <button
                        disabled={relationsCount > 0}
                        className="btn btn-icon btn-outline-danger">
                        <Delete16Regular />
                      </button> */}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </>
    );
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <>
        <div className="dashboard container pt-5 pb-5">
          <div className="row">
            <div className="col-12">
              <h4 className="-font-secondary -fw-700 -pb-3">Conditions</h4>
            </div>
            <div className="col-12 -pb-3">
              <Link
                className="btn btn-mobile btn-outline-dark -lg-mr-1"
                to={'/dashboard/edit/' + this.state.selectedSystemGuid}>
                Return to system
              </Link>
              {/* <Link
                className="btn btn-mobile btn-outline-dark"
                to={'/dashboard/sys/' + this.state.selectedSystemGuid}>
                Run the system
              </Link> */}
            </div>
          </div>

          {!this.state.contentLoaded ? <Loader center={false} /> : this.renderContent()}
        </div>
      </>
    );
  }
}

export default ORouter.bind(Conditions);
