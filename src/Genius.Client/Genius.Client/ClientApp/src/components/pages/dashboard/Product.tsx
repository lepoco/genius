/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { Link } from 'react-router-dom';
import { ConditionsInput } from '../../common/ConditionsInput';
import { ToastProvider, ToastType } from '../../common/Toasts';
import {
  Genius,
  ExpertSystem,
  IExpertSystem,
  IExpertProduct,
  ExpertProduct,
  IExpertCondition,
  ExpertRelations,
  IExpertRelations,
} from '../../../genius/Genius';
import Modal from '../../common/Modal';
import Loader from '../../common/Loader';

/**
 * Represents the variables contained in the Component state.
 */
interface IProductState {
  contentLoaded: boolean;

  selectedSystemGuid: string;

  productId: number;

  selectedSystem: IExpertSystem;

  selectedProduct: IExpertProduct;

  selectedProductRelations: IExpertRelations;
}

/**
 * Responsible for editing a single product.
 */
export class Product extends ORouter.PureComponent<IProductState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Product.name;

  private deleteModal: Modal | null = null;

  private selectedConditions: IExpertCondition[];

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      contentLoaded: false,
      selectedSystemGuid: props.router.params?.guid ?? '',
      productId: parseInt(props.router.params?.id ?? '0'),
      selectedSystem: new ExpertSystem(0, ''),
      selectedProduct: new ExpertProduct(0, 0),
      selectedProductRelations: new ExpertRelations(0, 0, [], [], []),
    };

    this.selectedConditions = [];

    this.renderContent = this.renderContent.bind(this);
    this.buttonDeleteOnClick = this.buttonDeleteOnClick.bind(this);
    this.buttonConfirmDeleteOnClick = this.buttonConfirmDeleteOnClick.bind(this);
    this.formOnSubmit = this.formOnSubmit.bind(this);
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
      true,
      true,
      true,
    );
    const product = await Genius.Api.getProduct(this.state.productId);
    const productRelations = await Genius.Api.getProductRelations(this.state.productId);

    if (system.id < 1 || product.id < 1) {
      return false;
    }

    // console.debug('\\Product\\populateData\\system', system);
    // console.debug('\\Product\\populateData\\product', product);
    // console.debug('\\Product\\populateData\\productRelations', productRelations);

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
        this.selectedConditions.push(con);
      }
    });

    this.setState({
      selectedSystem: system,
      selectedProduct: product,
      selectedProductRelations: productRelations,
      contentLoaded: true,
    });

    return true;
  }

  // private conditionsInputOnUpdate(
  //   selected: IExpertCondition[],
  //   available: IExpertCondition[],
  // ): void {
  //   this.selectedConditions = selected;
  // }

  private async buttonDeleteOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    event.preventDefault();

    if (this.deleteModal === null) {
      return false;
    }

    this.deleteModal.show();

    return true;
  }

  private async buttonConfirmDeleteOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    event.preventDefault();

    if (this.deleteModal === null) {
      return false;
    }

    this.deleteModal.show();

    const geniusResponse = await Genius.Api.deleteProduct(this.state.selectedProduct.id);

    if (!geniusResponse) {
      ToastProvider.show('Error!', 'Unable to delete product!', 5000, ToastType.Error);
      return false;
    }

    this.router.navigate('/dashboard/edit/' + this.state.selectedSystemGuid);

    return true;
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    const updateProductResponse: boolean = await Genius.Api.updateProduct(
      this.state.selectedProduct,
    );

    const updateConditionsResponse: boolean = await Genius.Api.updateProductConditions(
      this.state.selectedProduct,
      this.selectedConditions,
    );

    if (updateProductResponse && updateConditionsResponse) {
      ToastProvider.show(
        'Success!',
        'Product ' + this.state.selectedProduct.name + ' has been updated.',
        5000,
        ToastType.Success,
      );
    } else {
      ToastProvider.show('Error!', 'Product update failed.', 5000, ToastType.Error);
    }

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
              <select
                id="systemName"
                data-selected="fuzzy"
                className="floating-input__field"
                name="systemName"
                disabled={true}
                value={this.state.selectedSystem.id}
                placeholder="Type">
                <option value={this.state.selectedSystem.id}>
                  {'#' +
                    this.state.selectedSystem.id +
                    ' ' +
                    this.state.selectedSystem.name}
                </option>
              </select>
              <label htmlFor="systemName">System</label>
            </div>

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
              conditionsSelected={this.selectedConditions}
              onUpdate={(selected, available) => (this.selectedConditions = selected)}
            />

            <div className="-pb-2">
              <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
                Update
              </button>
              <button
                onClick={e => this.buttonDeleteOnClick(e)}
                type="button"
                className="btn btn-mobile btn-outline-danger -lg-mr-1">
                Delete
              </button>
              <Link
                to={'/dashboard/edit/' + (this.state.selectedSystemGuid ?? '0')}
                className="btn btn-outline-dark btn-mobile">
                Return to system
              </Link>
            </div>
          </form>
        </div>
      </div>
    );
  }

  private renderModalContent(): JSX.Element {
    return (
      <>
        <p>
          Are you sure you want to remove product{' '}
          <strong>{this.state.selectedProduct.name}</strong> completely?
        </p>
        <div>
          <button
            onClick={e => this.buttonConfirmDeleteOnClick(e)}
            type="button"
            className="btn btn-mobile btn-outline-danger -lg-mr-1">
            Delete
          </button>
          <button
            onClick={e => this.deleteModal?.hide()}
            type="button"
            className="btn btn-mobile btn-outline-dark -lg-mr-1">
            Cancel
          </button>
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
              <h4 className="-font-secondary -fw-700 -pb-3">Edit product</h4>
            </div>
          </div>

          {!this.state.contentLoaded ? <Loader center={false} /> : this.renderContent()}
        </div>
        <Modal
          name="product-delete"
          title="Delete product"
          ref={e => (this.deleteModal = e)}>
          {!this.state.contentLoaded ? <>Loading...</> : this.renderModalContent()}
        </Modal>
      </>
    );
  }
}

export default ORouter.bind(Product);
