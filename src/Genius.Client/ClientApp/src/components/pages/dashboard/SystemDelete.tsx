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

/**
 * Represents the variables contained in the component state.
 */
interface ISystemDeleteState {
  contentLoaded: boolean;
  acceptDelete: boolean;
  selectedSystem: IExpertSystem;
}

/**
 * It allows you to remove an expert system.
 */
export class SystemDelete extends ORouter.PureComponent<ISystemDeleteState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = SystemDelete.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      contentLoaded: false,
      acceptDelete: false,
      selectedSystem: new ExpertSystem(),
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
    const guid: string = this.router.params.guid ?? '';
    const selectedSystem: IExpertSystem = await Genius.Api.getSystemByGuid(guid);

    this.setState({
      selectedSystem: selectedSystem,
      contentLoaded: true,
    });

    return true;
  }

  private async inputOnChange(
    event: React.ChangeEvent<HTMLInputElement>,
  ): Promise<boolean> {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    // @ts-ignore
    this.setState({
      [name]: value,
    });

    return true;
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    if (this.state.acceptDelete !== true) {
      console.debug('\\SystemDelete\\formOnSubmit', 'Checkbox has not been checked');

      return false;
    }

    const apiResult = await Genius.Api.deleteSystem(this.state.selectedSystem.id ?? 0);

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
    if ((state.selectedSystem.id ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <form id="deleteSystem" method="POST" onSubmit={e => this.formOnSubmit(e)}>
        <input type="hidden" name="action" value="DeleteSystem" />
        <input type="hidden" name="nonce" value="@nonce('deletesystem')" />
        <input type="hidden" name="system_id" value="{{ $system->getId() ?? '0' }}" />
        <input type="hidden" name="system_uuid" value="{{ $system->getUUID() ?? '0' }}" />

        <div className="-reveal">
          <span>System name</span>
          <h5 className="-font-secondary -fw-700">{state.selectedSystem.name ?? ''}</h5>
        </div>

        <div className="-reveal">
          <span>Creation date:</span>
          <h5 className="-font-secondary -fw-700 -pb-3">
            {state.selectedSystem.createdAt ?? '__unknown'}
          </h5>
        </div>

        <div className="form-check -mb-2">
          <input
            type="checkbox"
            className="form-check-input"
            id="accept_delete"
            name="acceptDelete"
            value="acceptDelete"
            onChange={e => this.inputOnChange(e)}
          />
          <label htmlFor="acceptDelete">
            Aware of the irreversibility of the changes, I want to delete the saved data.
          </label>
        </div>

        <div className="-reveal -pb-1">
          <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
            Delete
          </button>
          <Link
            to={'/dashboard/edit/' + state.selectedSystem.guid ?? '#'}
            className="btn btn-outline-dark btn-mobile">
            Cancel
          </Link>
        </div>
      </form>
    );
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    const contents = !this.state.contentLoaded ? (
      <Loader center={false} />
    ) : (
      this.renderContent(this.state)
    );

    return (
      <div className="row">
        <div className="col-12">
          <h4 className="-font-secondary -fw-700 -pb-3">Remove the expert system</h4>
        </div>

        <div className="col-12 -mb-3">
          <strong>
            Deleting the database records is irreversible, make sure you delete the
            correct system.
          </strong>
        </div>
        <div className="col-12">{contents}</div>
      </div>
    );
  }
}

export default ORouter.bind(SystemDelete);
