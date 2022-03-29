/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import {
  Genius,
  ExpertSystem,
  IExpertSystem,
  ExpertCondition,
  IExpertCondition,
  ExpertRelations,
  IExpertRelations,
} from '../../../genius/Genius';
import RoutedPureComponent from '../../../common/RoutedPureComponent';
import IRouterProps from '../../../interfaces/IRouterProps';
import withRouter from '../../../common/withRouter';
import Modal from '../../common/Modal';
import Loader from '../../common/Loader';

/**
 * Represents the variables contained in the Component state.
 */
interface IConditionState {
  contentLoaded: boolean;

  selectedSystemGuid: string;

  selectedSystem: IExpertSystem;

  selectedConditionId: number;

  selectedCondition: IExpertCondition;

  selectedConditionRelations: IExpertRelations;
}

/**
 * Dashboard - Condition page Component.
 */
export class Condition extends RoutedPureComponent<IConditionState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Condition.name;

  private deleteModal: Modal | null = null;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      contentLoaded: false,
      selectedSystemGuid: props.router.params?.guid ?? '',
      selectedConditionId: parseInt(props.router.params?.id ?? '0'),
      selectedSystem: new ExpertSystem(),
      selectedCondition: new ExpertCondition(),
      selectedConditionRelations: new ExpertRelations(),
    };

    this.formOnSubmit = this.formOnSubmit.bind(this);
    this.buttonDeleteOnClick = this.buttonDeleteOnClick.bind(this);
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
    const system = await Genius.Api.getSystemByGuid(this.state.selectedSystemGuid);
    const condition = await Genius.Api.getCondition(this.state.selectedConditionId);
    const conditionRelations = await Genius.Api.getConditionRelations(
      this.state.selectedConditionId,
    );

    if (condition.id < 1) {
      return false;
    }

    this.setState({
      selectedSystem: system,
      selectedCondition: condition,
      selectedConditionRelations: conditionRelations,
      contentLoaded: true,
    });

    return true;
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    console.debug('\\Condition\\formOnSubmit\\event', event);

    return true;
  }

  private async buttonDeleteOnClick(event): Promise<boolean> {
    event.preventDefault();

    if (this.deleteModal === null) {
      return false;
    }

    this.deleteModal.show();

    return true;
  }

  public renderContent(): JSX.Element {
    return (
      <>
        <div className="row">
          <div className="col-12">
            <div className=" -pb-3">
              <h5 className="-font-secondary -fw-700">
                {'#' +
                  this.state.selectedCondition.id +
                  ' - ' +
                  this.state.selectedCondition.name}
              </h5>
              <p className="-font-secondary -pm-0">
                System: <strong>{this.state.selectedSystem.name}</strong>
              </p>
              <p className="-font-secondary -pm-0">
                Relations count: {this.state.selectedConditionRelations.count()}
              </p>
            </div>
          </div>
          <div className="col-12">
            <form onSubmit={e => this.formOnSubmit(e)}>
              <div className="floating-input">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Name"
                  defaultValue={this.state.selectedCondition.name}
                  onChange={event => {
                    this.state.selectedCondition.name = event.target.value;
                  }}
                  name="condition_name"
                />
                <label htmlFor="condition_name">Name</label>
              </div>
              <div className="floating-input">
                <input
                  className="floating-input__field"
                  type="text"
                  placeholder="Description"
                  defaultValue={this.state.selectedCondition.description}
                  onChange={event => {
                    this.state.selectedCondition.description = event.target.value;
                  }}
                  name="condition_description"
                />
                <label htmlFor="condition_description">Description</label>
              </div>

              <button type="submit" className="btn btn-mobile btn-dark -lg-mr-1">
                Update
              </button>
              <button
                onClick={e => this.buttonDeleteOnClick(e)}
                type="button"
                className="btn btn-mobile btn-outline-danger -lg-mr-1">
                Delete
              </button>
              <Link
                className="btn btn-mobile btn-outline-dark -lg-mr-1"
                to={'/dashboard/conditions/' + this.state.selectedSystemGuid}>
                Return to all conditions
              </Link>
              <Link
                className="btn btn-mobile btn-outline-dark"
                to={'/dashboard/edit/' + this.state.selectedSystemGuid}>
                Return to system
              </Link>
            </form>
          </div>
        </div>
      </>
    );
  }

  private renderModalContent(): JSX.Element {
    return <>Modal</>;
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
              <h4 className="-font-secondary -fw-700 -pb-3">Edit condition</h4>
            </div>
          </div>

          {!this.state.contentLoaded ? <Loader center={false} /> : this.renderContent()}
        </div>
        <Modal
          name="condition-delete"
          title="Delete condition"
          ref={e => (this.deleteModal = e)}>
          {!this.state.contentLoaded ? <>Loading...</> : this.renderModalContent()}
        </Modal>
      </>
    );
  }
}

export default withRouter(Condition);
