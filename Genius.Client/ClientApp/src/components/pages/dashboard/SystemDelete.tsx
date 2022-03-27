/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import Loader from '../../common/Loader';
import RoutedPureComponent from '../../../common/RoutedPureComponent';
import withRouter from '../../../common/withRouter';
import IRouterProps from '../../../interfaces/IRouterProps';
import IExpertPageState from '../../../genius/interfaces/IExpertPageState';
import GeniusApi from '../../../genius/GeniusApi';

/**
 * Represents the variables contained in the component state.
 */
interface ISystemDeleteState extends IExpertPageState {
  acceptDelete?: boolean;
}

/**
 * It allows you to remove an expert system.
 */
export class SystemDelete extends RoutedPureComponent<ISystemDeleteState> {
  /**
   * The display name of the component.
   */
  public static displayName: string = SystemDelete.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
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

    this.formOnSubmit = this.formOnSubmit.bind(this);
    this.inputOnChange = this.inputOnChange.bind(this);
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

    return true;
  }

  private async inputOnChange(
    event: React.ChangeEvent<HTMLInputElement>,
  ): Promise<boolean> {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });

    return true;
  }

  private async formOnSubmit(
    event: React.FormEvent<HTMLFormElement>,
  ): Promise<boolean> {
    event.preventDefault();

    if (this.state.acceptDelete !== true) {
      console.debug(
        '\\SystemDelete\\formOnSubmit',
        'Checkbox has not been checked',
      );

      return false;
    }

    let apiResult = await GeniusApi.deleteSystem(this.state.systemId ?? 0);

    console.debug('\\SystemDelete\\formOnSubmit', apiResult);

    if (apiResult) {
      this.router.navigate('/dashboard');
    }

    return true;
  }

  /**
   * Renders the content containing data downloaded from the server.
   */
  private renderContent(state: ISystemDeleteState): JSX.Element {
    if ((state.systemId ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <form
        id="deleteSystem"
        method="POST"
        onSubmit={e => this.formOnSubmit(e)}>
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
            onChange={e => this.inputOnChange(e)}
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

  /**
   * The main method responsible for refreshing the view.
   */
  public render(): JSX.Element {
    const contents = !this.state.systemLoaded ? (
      <Loader center={false} />
    ) : (
      this.renderContent(this.state)
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
