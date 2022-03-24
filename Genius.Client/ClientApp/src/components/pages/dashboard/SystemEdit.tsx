/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import { FloatingTags } from '../../common/FloatingTags';
import Loader from '../../common/Loader';
import Modal from '../../common/Modal';
import RoutedComponent from '../../../common/RoutedComponent';
import IRouterProps from '../../../interfaces/IRouterProps';
import withRouter from '../../../common/withRouter';
import IExpertPageState from '../../../genius/IExpertPageState';
import IExpertCondition from '../../../genius/IExpertCondition';
import GeniusApi from '../../../genius/GeniusApi';
import ExpertProduct from '../../../genius/ExpertProduct';
import IExpertProduct from '../../../genius/IExpertProduct';

class ProductWithConditions {
  id: number = 0;

  name: string = '';

  description: string = '';

  notes: string = '';

  conditions: IExpertCondition[] = [];
}

class SystemEdit extends RoutedComponent<IExpertPageState> {
  public static displayName: string = SystemEdit.name;

  private newProduct: ProductWithConditions = new ProductWithConditions();

  private conditionsCloud: FloatingTags | null = null;

  private productNameInput: HTMLInputElement | null = null;

  private productDescriptionInput: HTMLInputElement | null = null;

  private productNotesInput: HTMLInputElement | null = null;

  private importModal: Modal | null = null;

  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
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

    this.importOnClick = this.importOnClick.bind(this);
  }

  public componentDidMount(): void {
    this.populateExpertSystemData();
  }

  private async populateExpertSystemData(): Promise<void> {
    const system = await GeniusApi.getSystemByGuid(
      this.router.params.guid ?? '',
      true,
      true,
      true,
    );

    this.setState({
      systemLoaded: true,

      systemId: system.systemId ?? 0,
      systemVersion: system.systemVersion ?? '',
      systemName: system.systemName ?? '',
      systemDescription: system.systemDescription ?? '',
      systemGuid: system.systemGuid ?? '',
      systemQuestion: system.systemQuestion ?? '',
      systemType: system.systemType ?? '',
      systemCreatedAt: system.systemCreatedAt ?? '',
      systemUpdatedAt: system.systemUpdatedAt ?? '',
      systemConditions: system.systemConditions ?? [],
      systemProducts: system.systemProducts ?? [],
      systemRelations: system.systemRelations ?? [],
    });
  }

  private importOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): void {
    console.log(event);
    if (this.importModal == null) return;

    this.importModal.show();
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

    if (this.state.systemId === undefined) {
      return;
    }

    if (this.newProduct.name === '' || this.state.systemId < 1) {
      return;
    }

    if (this.newProduct.conditions.length < 1) {
      return;
    }

    let productToAdd = new ExpertProduct(
      0,
      this.state.systemId,
      this.newProduct.name,
      this.newProduct.description,
      this.newProduct.notes,
    );

    let apiResult = await GeniusApi.addProductWithConditions(
      productToAdd,
      this.newProduct.conditions,
    );

    if (apiResult < 1) {
      return;
    }

    productToAdd.id = apiResult;

    let currentProducts = this.state.systemProducts;
    currentProducts?.push(productToAdd);

    let updatedRelations = await GeniusApi.getSystemRelations(
      this.state.systemId,
    );

    this.setState({
      systemRelations: updatedRelations,
      systemProducts: currentProducts,
    });

    console.debug(
      '\\SystemEdit\\handleSubmit\\relations',
      this.state.systemRelations,
    );

    this.resetForm();
  }

  private resetForm(): void {
    this.newProduct = new ProductWithConditions();

    if (this.productNameInput !== null) {
      this.productNameInput.value = '';
    }

    if (this.productDescriptionInput !== null) {
      this.productDescriptionInput.value = '';
    }

    if (this.productNotesInput !== null) {
      this.productNotesInput.value = '';
    }

    if (this.conditionsCloud !== null) {
      this.conditionsCloud.clear();
    }
  }

  private conditionsUpdated(
    options: IExpertCondition[],
    selected: IExpertCondition[],
  ): void {
    this.newProduct.conditions = selected;
  }

  private renderProductsTable(state: IExpertPageState): JSX.Element {
    let products: IExpertProduct[] = state.systemProducts ?? [];

    if (products.length < 1) {
      return (
        <tbody>
          <tr></tr>
        </tbody>
      );
    }

    return (
      <tbody>
        {products.map((singleProduct, i) => {
          let productRelations = (state.systemRelations ?? []).filter(
            element => element.productId === singleProduct.id,
          );

          console.debug('Product relations', productRelations);

          return (
            <tr key={singleProduct.id ?? 0}>
              <th scope="row">{singleProduct.id ?? '0'}</th>
              <td>{singleProduct.name ?? ''}</td>
              <td>
                {(singleProduct.description ?? '') === '' ? (
                  '---'
                ) : (
                  <i>{singleProduct.description}</i>
                )}
              </td>
              <td>
                {(singleProduct.notes ?? '') === '' ? (
                  '---'
                ) : (
                  <i>{singleProduct.notes}</i>
                )}
              </td>
              <td>---</td>
            </tr>
          );
        })}
      </tbody>
    );
  }

  private renderSystemView(state: IExpertPageState): JSX.Element {
    if ((state.systemId ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    let productsTable = this.renderProductsTable(this.state);

    return (
      <div className="row">
        <div className="col-12">
          <div className="-reveal">
            <span>System name</span>
            <h5 className="-font-secondary -fw-700">
              {state.systemName ?? ''}
            </h5>
          </div>
          <div className="-reveal">
            <span>Creation date:</span>
            <h5 className="-font-secondary -fw-700 -pb-3">
              {state.systemCreatedAt ?? '__unknown'}
            </h5>
          </div>

          <div className="-mb-3 -reveal">
            <a
              href={'/export/' + state.systemGuid ?? '#'}
              className="btn btn-dark btn-mobile -lg-mr-1 -btn-export">
              Export
            </a>
            <button
              className="btn btn-outline-dark btn-mobile -btn-import -lg-mr-1"
              onClick={e => this.importOnClick(e)}>
              Import
            </button>
            <Link to={'/dashboard/delete/' + state.systemGuid ?? '#'}>
              Remove the expert system
            </Link>
          </div>
        </div>

        <div className="col-12 -mb-2">
          <hr />
        </div>

        <div className="col-12">
          <form
            id="addProduct"
            method="POST"
            onSubmit={this.handleSubmit.bind(this)}>
            <h5 className="-font-secondary -fw-700 -pb-1 -reveal">
              New product
            </h5>

            <div className="-reveal">
              <p>
                Product is the final result of the application's operation. If
                the purpose of the system is to select the best garden gnomes,
                the product can be - <i>"Red Gnome by iGnome INC"</i>.
              </p>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Name"
                name="product_name"
                defaultValue={this.newProduct.name}
                onChange={event => {
                  this.newProduct.name = event.target.value;
                }}
                ref={element => {
                  this.productNameInput = element;
                }}
              />
              <label htmlFor="product_name">Name</label>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Description"
                defaultValue={this.newProduct.description}
                onChange={event => {
                  this.newProduct.description = event.target.value;
                }}
                ref={element => {
                  this.productDescriptionInput = element;
                }}
                name="product_description"
              />
              <label htmlFor="product_description">Description</label>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Notes"
                defaultValue={this.newProduct.notes}
                onChange={event => {
                  this.newProduct.notes = event.target.value;
                }}
                ref={element => {
                  this.productNotesInput = element;
                }}
                name="product_notes"
              />
              <label htmlFor="product_notes">Notes</label>
            </div>

            <p>
              <small>
                <i>
                  Tip: At the bottom you can see all conditions already
                  available in the database, you can add a new condition by
                  typing its name and pressing enter.
                </i>
              </small>
            </p>

            <FloatingTags
              name="product_conditions_confirming"
              header="Conditions (confirming)"
              ref={element => {
                this.conditionsCloud = element;
              }}
              systemId={this.state.systemId ?? 0}
              options={this.state.systemConditions ?? []}
              selected={[]}
              onUpdate={this.conditionsUpdated.bind(this)}
            />

            {/* <FloatingTags
              name="product_conditions_negating"
              header="Conditions (negating)"
              systemId={this.state.systemId ?? 0}
              options={this.state.systemConditions ?? []}
              selected={[]}
            /> */}

            <div className="-reveal -pb-2">
              <button
                type="submit"
                className="btn btn-dark btn-mobile -lg-mr-1">
                Add
              </button>
            </div>
          </form>
        </div>

        <div className="col-12">
          <hr />
        </div>

        <div className="col-12">
          <h5 className="-font-secondary -fw-700 -pb-1 -reveal">Products</h5>
        </div>

        <table className="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Notes</th>
              <th scope="col">Conditions</th>
            </tr>
          </thead>
          {productsTable}
        </table>
      </div>
    );
  }

  public render(): JSX.Element {
    let contents = !this.state.systemLoaded ? (
      <Loader />
    ) : (
      this.renderSystemView(this.state)
    );

    return (
      <>
        <div className="dashboard container pt-5 pb-5">
          <div className="row">
            <div className="col-12">
              <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Edit</h4>
            </div>
          </div>

          {contents}
        </div>
        <Modal
          name="system-import"
          title="Import Expert System"
          ref={element => {
            this.importModal = element;
          }}>
          <div>
            <p>123</p>
          </div>
        </Modal>
      </>
    );
  }
}

export default withRouter(SystemEdit);
