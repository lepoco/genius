/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import Loader from '../../common/Loader';
import RoutedComponent from '../../../common/RoutedComponent';
import withRouter from '../../../common/withRouter';
import IRouterProps from '../../../interfaces/IRouterProps';
import IExpertPageState from '../../../genius/IExpertPageState';
import GeniusApi from '../../../genius/GeniusApi';

interface IDeleteExpertState extends IExpertPageState {
  acceptDelete?: boolean;
}

class SystemDelete extends RoutedComponent<IDeleteExpertState> {
  public static displayName: string = SystemDelete.name;

  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      acceptDelete: false,
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
  }

  public componentDidMount(): void {
    this.populateExpertSystemData();
  }

  private async populateExpertSystemData(): Promise<void> {
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

    if (this.state.acceptDelete !== true) {
      console.debug(
        '\\SystemDelete\\handleSubmit',
        'Checkbox has not been checked',
      );

      return;
    }

    let apiResult = await GeniusApi.deleteSystem(this.state.systemId ?? 0);

    console.debug('\\SystemDelete\\handleSubmit', apiResult);

    if (apiResult) {
      this.router.navigate('/dashboard');
    }
  }

  private renderSystemView(state: IDeleteExpertState): JSX.Element {
    if ((state.systemId ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <form
        id="deleteSystem"
        method="POST"
        onSubmit={this.handleSubmit.bind(this)}>
        <input type="hidden" name="action" value="DeleteSystem" />
        <input type="hidden" name="nonce" value="@nonce('deletesystem')" />
        <input
          type="hidden"
          name="system_id"
          value="{{ $system->getId() ?? '0' }}"
        />
        <input
          type="hidden"
          name="system_uuid"
          value="{{ $system->getUUID() ?? '0' }}"
        />

        <div className="-reveal">
          <span>System name</span>
          <h5 className="-font-secondary -fw-700">{state.systemName ?? ''}</h5>
        </div>

        <div className="-reveal">
          <span>Creation date:</span>
          <h5 className="-font-secondary -fw-700 -pb-3">
            {state.systemCreatedAt ?? '__unknown'}
          </h5>
        </div>

        <div className="form-check -reveal -mb-2">
          <input
            type="checkbox"
            className="form-check-input"
            id="accept_delete"
            name="acceptDelete"
            value="acceptDelete"
            onChange={this.handleInputChange.bind(this)}
          />
          <label htmlFor="acceptDelete">
            Aware of the irreversibility of the changes, I want to delete the
            saved data.
          </label>
        </div>

        <div className="-reveal -pb-1">
          <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
            Delete
          </button>
          <Link
            to={'/dashboard/edit/' + state.systemGuid ?? '#'}
            className="btn btn-outline-dark btn-mobile">
            Cancel
          </Link>
        </div>
      </form>
    );
  }

  public render(): JSX.Element {
    let contents = !this.state.systemLoaded ? (
      <Loader />
    ) : (
      this.renderSystemView(this.state)
    );

    return (
      <div className="row">
        <div className="col-12">
          <h4 className="-font-secondary -fw-700 -pb-3 -reveal">
            Remove the expert system
          </h4>
        </div>

        <div className="col-12 -mb-3 -reveal">
          <strong>
            Deleting the database records is irreversible, make sure you delete
            the correct system.
          </strong>
        </div>
        <div className="col-12">{contents}</div>
      </div>
    );
  }
}

export default withRouter(SystemDelete);
