/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import { ConditionsInput } from '../../common/ConditionsInput';
import Loader from '../../common/Loader';
import Modal from '../../common/Modal';
import RoutedPureComponent from '../../../common/RoutedPureComponent';
import IRouterProps from '../../../interfaces/IRouterProps';
import withRouter from '../../../common/withRouter';
import IExpertPageState from '../../../genius/interfaces/IExpertPageState';
import IExpertCondition from '../../../genius/interfaces/IExpertCondition';
import GeniusApi from '../../../genius/GeniusApi';
import ExpertProduct from '../../../genius/ExpertProduct';
import IExpertProduct from '../../../genius/interfaces/IExpertProduct';
import Task from '../../common/Task';
import ImportRequest from '../../../genius/ImportRequest';
import { Edit16Regular } from '@fluentui/react-icons';

/**
 * Represents the variables contained in the component state.
 */
interface ISystemEditState extends IExpertPageState {
  importing?: boolean;
}

class ProductWithConditions {
  id: number = 0;

  name: string = '';

  description: string = '';

  notes: string = '';

  conditions: IExpertCondition[] = [];
}

/**
 * It allows for editing the expert system.
 */
class SystemEdit extends RoutedPureComponent<ISystemEditState> {
  public static displayName: string = SystemEdit.name;

  private newProduct: ProductWithConditions = new ProductWithConditions();

  private editedProduct: ProductWithConditions = new ProductWithConditions();

  private conditionsCloud: ConditionsInput | null = null;

  private productEditConditionsCloud: ConditionsInput | null = null;

  private productNameInput: HTMLInputElement | null = null;

  private productDescriptionInput: HTMLInputElement | null = null;

  private productNotesInput: HTMLInputElement | null = null;

  private importModal: Modal | null = null;

  private productModal: Modal | null = null;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      importing: false,
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

    this.importButtonOnClick = this.importButtonOnClick.bind(this);
    this.importInputOnChange = this.importInputOnChange.bind(this);
    this.editProductButtonOnClick = this.editProductButtonOnClick.bind(this);
    this.conditionsInputOnUpdate = this.conditionsInputOnUpdate.bind(this);
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

    return true;
  }

  private async importButtonOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    console.log(event);
    if (this.importModal == null) return false;

    this.importModal.show();

    return true;
  }

  private async importInputOnChange(
    event: React.ChangeEvent<HTMLInputElement>,
  ): Promise<boolean> {
    if (event.target.files == null) return false;

    const fileList = event.target.files;

    if (fileList.length < 1) return false;

    const selectedFile = fileList[0];

    event.target.value = '';

    if (!selectedFile) return false;

    this.setState({ importing: true });

    // let selectedFileContent = await selectedFile.text();
    // selectedFileContent = selectedFileContent.trim();

    // if(selectedFileContent === ''){
    //   // FAILED, EMPTY

    //   this.setState({ importing: false });

    //   return false;
    // }

    const importResponse = await GeniusApi.importFromFile(
      new ImportRequest(this.state.systemId ?? 0, selectedFile),
    );

    console.log(importResponse);

    await Task.delay(1000);

    this.setState({ importing: false });

    return true;
  }

  private async editProductButtonOnClick(
    productId: number,
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    if (
      productId < 1 ||
      this.productModal === null ||
      this.state.systemProducts === undefined
    )
      return false;
    if (this.state.systemProducts.length < 1) return false;

    let selectedProduct = this.state.systemProducts.find(prod => prod.id === productId);

    if (selectedProduct === undefined) return false;

    console.debug('\\SystemEdit\\editProductButtonOnClick\\event', event);
    console.debug(
      '\\SystemEdit\\editProductButtonOnClick\\selectedProduct',
      selectedProduct,
    );

    this.editedProduct.name = selectedProduct.name ?? '';
    this.editedProduct.description = selectedProduct.description ?? '';
    this.editedProduct.notes = selectedProduct.notes ?? '';

    const productRelations = await GeniusApi.getProductRelations(selectedProduct.id ?? 0);

    console.debug(
      '\\SystemEdit\\editProductButtonOnClick\\productRelations',
      productRelations,
    );

    //this.editedProduct.conditions =

    this.productModal.show();

    return true;
  }

  private handleInputChange(event): void {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  private async handleUpdateProductSubmit(event): Promise<boolean> {
    return false;
  }

  private async formOnSubmit(event): Promise<boolean> {
    event.preventDefault();

    if (this.state.systemId === undefined) {
      return false;
    }

    if (this.newProduct.name === '' || this.state.systemId < 1) {
      return false;
    }

    if (this.newProduct.conditions.length < 1) {
      return false;
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
      return false;
    }

    productToAdd.id = apiResult;

    let currentProducts = this.state.systemProducts;
    currentProducts?.push(productToAdd);

    let updatedRelations = await GeniusApi.getSystemRelations(this.state.systemId);

    this.setState({
      systemRelations: updatedRelations,
      systemProducts: currentProducts,
    });

    console.debug('\\SystemEdit\\formOnSubmit\\relations', this.state.systemRelations);

    this.resetForm();

    return true;
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

  private conditionsInputOnUpdate(
    selected: IExpertCondition[],
    available: IExpertCondition[],
  ): void {
    console.debug('\\SystemEdit\\conditionsInputOnUpdate\\selected', selected);
    this.newProduct.conditions = selected;
  }

  private renderProductsTable(): JSX.Element {
    let products: IExpertProduct[] = this.state.systemProducts ?? [];

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
          // let productRelations = (this.state.systemRelations ?? []).filter(
          //   element => element.productId === singleProduct.id,
          // );

          // console.debug('Product relations', productRelations);

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
              {/* <td>---</td> */}
              <td>
                <Link
                  to={
                    '/dashboard/product/' +
                    (this.state.systemGuid ?? '0') +
                    '/' +
                    (singleProduct.id ?? '0')
                  }
                  className="btn btn-icon btn-outline-dark">
                  <Edit16Regular />
                </Link>
              </td>
            </tr>
          );
        })}
      </tbody>
    );
  }

  /**
   * Renders the content containing data downloaded from the server.
   */
  private renderContent(state: IExpertPageState): JSX.Element {
    if ((state.systemId ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <div className="row">
        <div className="col-12">
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

          <div className="-mb-3 -reveal">
            <a
              href={'/api/export/' + state.systemGuid ?? '#'}
              className="btn btn-dark btn-mobile -lg-mr-1 -btn-export">
              Export
            </a>
            <button
              className="btn btn-outline-dark btn-mobile -btn-import -lg-mr-1"
              onClick={e => this.importButtonOnClick(e)}>
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
          <form id="addProduct" method="POST" onSubmit={this.formOnSubmit.bind(this)}>
            <h5 className="-font-secondary -fw-700 -pb-1 -reveal">New product</h5>

            <div className="-reveal">
              <p>
                Product is the final result of the application's operation. If the purpose
                of the system is to select the best garden gnomes, the product can be -{' '}
                <i>"Red Gnome by iGnome INC"</i>.
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
                  Tip: At the bottom you can see all conditions already available in the
                  database, you can add a new condition by typing its name and pressing
                  enter.
                </i>
              </small>
            </p>

            <ConditionsInput
              inputName="Conditions (confirming)"
              ref={element => {
                this.conditionsCloud = element;
              }}
              systemId={this.state.systemId ?? 0}
              conditionsSelected={[]}
              onUpdate={this.conditionsInputOnUpdate}
            />

            {/* <ConditionsInput
              inputName="Conditions (negating)"
              systemId={this.state.systemId ?? 0}
              conditionsSelected={[]}
            /> */}

            <div className="-reveal -pb-2">
              <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
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

        <div className="col-12">
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Description</th>
                  <th scope="col">Notes</th>
                  <th scope="col"></th> {/* Actions */}
                </tr>
              </thead>
              {this.renderProductsTable()}
            </table>
          </div>
        </div>
      </div>
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
            {this.state.importing ? (
              <div className="d-flex justify-content-center">
                <Loader center={true} />
              </div>
            ) : (
              <>
                <p className="notice">
                  The content of the selected file will be imported to the currently
                  edited expert system. The importer will try to skip duplicate entries
                  automatically, however, it is recommended to import the file into a
                  blank project.
                </p>
                <div className="mb-3">
                  <label htmlFor="formFile" className="form-label">
                    Select <code>.genius</code> file.
                  </label>
                  <input
                    accept=".genius"
                    className="form-control"
                    type="file"
                    id="formFile"
                    onChange={e => this.importInputOnChange(e)}
                  />
                </div>
              </>
            )}
          </div>
        </Modal>
      </>
    );
  }
}

export default withRouter(SystemEdit);
