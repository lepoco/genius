/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import { ConditionsInput } from '../../common/ConditionsInput';
import {
  Genius,
  ExpertSystem,
  IExpertSystem,
  IExpertProduct,
  ExpertProduct,
  ExpertCondition,
  IExpertCondition,
  ExpertRelations,
  IExpertRelations,
} from '../../../genius/Genius';
import RoutedPureComponent from '../../../common/RoutedPureComponent';
import IRouterProps from '../../../interfaces/IRouterProps';
import Loader from '../../common/Loader';
import withRouter from '../../../common/withRouter';

/**
 * Represents the variables contained in the Component state.
 */
interface IProductState {
  contentLoaded: boolean;

  systemGUID: string;

  productId: number;

  selectedSystem: IExpertSystem;

  selectedProduct: IExpertProduct;

  selectedProductRelations: IExpertRelations;

  selectedProductConditions: IExpertCondition[];
}

/**
 * Responsible for editing a single product.
 */
export class Product extends RoutedPureComponent<IProductState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Product.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      contentLoaded: false,
      systemGUID: props.router.params?.guid ?? '',
      productId: parseInt(props.router.params?.id ?? '0'),
      selectedProductConditions: [],
      selectedSystem: new ExpertSystem(0, ''),
      selectedProduct: new ExpertProduct(0, 0),
      selectedProductRelations: new ExpertRelations(0, 0, [], [], []),
    };

    this.renderContent = this.renderContent.bind(this);
    this.formOnSubmit = this.formOnSubmit.bind(this);

    console.debug('Props', props);
    console.debug('State', this.state);
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
      this.state.systemGUID,
      true,
      true,
      true,
    );
    const product = await Genius.Api.getProduct(this.state.productId);
    const productRelations = await Genius.Api.getProductRelations(this.state.productId);

    if (system.id < 1 || product.id < 1) {
      return false;
    }

    console.debug('\\Product\\populateData\\system', system);
    console.debug('\\Product\\populateData\\product', product);
    console.debug('\\Product\\populateData\\productRelations', productRelations);

    let selectedProductConfirmingRelations: number[] = productRelations.confirming;
    let selectedConditionsFromRelations: number[] = [];
    let selectedProductConditions: IExpertCondition[] = [];

    system.relations?.forEach(rel => {
      if (rel.id === undefined || rel.id < 1) {
        return;
      }

      if (
        selectedProductConfirmingRelations.includes(rel.id) &&
        rel.conditionId !== undefined &&
        rel.conditionId > 0
      ) {
        selectedConditionsFromRelations.push(rel.conditionId);
      }
    });

    system.conditions?.forEach(con => {
      if (con.id === undefined || con.id < 1) {
        return;
      }

      if (selectedConditionsFromRelations.includes(con.id)) {
        selectedProductConditions.push(con);
      }
    });

    this.setState({
      selectedSystem: system,
      selectedProduct: product,
      selectedProductRelations: productRelations,
      selectedProductConditions: selectedProductConditions,
      contentLoaded: true,
    });

    return true;
  }

  private conditionsInputOnUpdate(
    selected: IExpertCondition[],
    available: IExpertCondition[],
  ): void {
    this.setState({
      selectedProductConditions: selected,
    });
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    console.debug(event);
    console.debug(this.state);

    return true;
  }

  /**
   * Renders the content containing data downloaded from the server.
   */
  private renderContent(): JSX.Element {
    return (
      <div className="row">
        <div className="col-12">
          <div>
            <h5 className="-font-secondary -fw-700">
              {this.state.selectedProduct.name ?? ''}
            </h5>
          </div>
          <div>
            <p className="-font-secondary -pb-3">
              {this.state.selectedProduct.description ?? ''}
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
                name="product_name"
                defaultValue={this.state.selectedProduct.name}
                onChange={event => {
                  this.state.selectedProduct.name = event.target.value;
                }}
              />
              <label htmlFor="product_name">Name</label>
            </div>

            <div className="floating-input">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Description"
                defaultValue={this.state.selectedProduct.description}
                onChange={event => {
                  this.state.selectedProduct.description = event.target.value;
                }}
                name="product_description"
              />
              <label htmlFor="product_description">Description</label>
            </div>

            <div className="floating-input">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Notes"
                defaultValue={this.state.selectedProduct.notes}
                onChange={event => {
                  this.state.selectedProduct.notes = event.target.value;
                }}
                name="product_notes"
              />
              <label htmlFor="product_notes">Notes</label>
            </div>

            <p>
              <small>
                <i>
                  Tip: At the bottom you can see all conditions already available in the
                  database, you can add a new condition by typing its name and pressing
                  enter.
                  <br />
                  You can add multiple conditions by entering their names after a comma.
                  If any of the added conditions already exist, no copy will be made.
                </i>
              </small>
            </p>

            <ConditionsInput
              inputName="Conditions (confirming)"
              systemId={this.state.selectedSystem.id ?? 0}
              conditionsSelected={this.state.selectedProductConditions ?? []}
              onUpdate={this.conditionsInputOnUpdate}
            />

            <div className="-pb-2">
              <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
                Update
              </button>
              <Link
                to={'/dashboard/edit/' + (this.state.systemGUID ?? '0')}
                className="btn btn-outline-dark btn-mobile">
                Return to system
              </Link>
            </div>
          </form>
        </div>
      </div>
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
              <h4 className="-font-secondary -fw-700 -pb-3">Edit product</h4>
            </div>
          </div>

          {!this.state.contentLoaded ? <Loader center={false} /> : this.renderContent()}
        </div>
      </>
    );
  }
}

export default withRouter(Product);
