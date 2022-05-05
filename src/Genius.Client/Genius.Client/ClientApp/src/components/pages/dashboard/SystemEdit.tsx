/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { Link } from 'react-router-dom';
import { ConditionsInput } from '../../common/ConditionsInput';
import { ToastProvider } from '../../common/Toasts';
import {
  Genius,
  IExpertCondition,
  ExpertProduct,
  IExpertProduct,
  ImportRequest,
} from '../../../genius/Genius';
import { IExpertPageState } from '../../../genius/interfaces/IExpertPageState';
import Task from '../../common/Task';
import Loader from '../../common/Loader';
import Modal from '../../common/Modal';
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
 * Allows for editing the expert system.
 */
class SystemEdit extends ORouter.PureComponent<ISystemEditState> {
  public static displayName: string = SystemEdit.name;

  private newProduct: ProductWithConditions = new ProductWithConditions();

  private editedProduct: ProductWithConditions = new ProductWithConditions();

  private conditionsCloud: ConditionsInput | null = null;

  private productNameInput: HTMLInputElement | null = null;

  private productDescriptionInput: HTMLInputElement | null = null;

  private productNotesInput: HTMLInputElement | null = null;

  private importModal: Modal | null = null;

  private productModal: Modal | null = null;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      importing: false,
      systemLoaded: false,
      id: 0,
      guid: '',
      version: '',
      name: '',
      description: '',
      type: '',
      question: '',
      createdAt: '',
      updatedAt: '',
      conditions: [],
      products: [],
      relations: [],
      productsCount: 0,
      conditionsCount: 0,
      relationsCount: 0,
    };

    this.formOnSubmit = this.formOnSubmit.bind(this);
    this.importButtonOnClick = this.importButtonOnClick.bind(this);
    this.importInputOnChange = this.importInputOnChange.bind(this);
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
    const system = await Genius.Api.getSystemByGuid(
      this.router.params.guid ?? '',
      true,
      true,
      true,
    );

    this.setState({
      systemLoaded: true,

      id: system.id ?? 0,
      version: system.version ?? '',
      name: system.name ?? '',
      description: system.description ?? '',
      guid: system.guid ?? '',
      question: system.question ?? '',
      type: system.type ?? '',
      createdAt: system.createdAt ?? '',
      updatedAt: system.updatedAt ?? '',
      conditions: system.conditions ?? [],
      products: system.products ?? [],
      relations: system.relations ?? [],
    });

    return true;
  }

  private async importButtonOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
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

    const importResponse = await Genius.Api.importFromFile(
      new ImportRequest(this.state.id ?? 0, selectedFile),
    );

    console.log('\\SystemEdit\\importInputOnChange\\importResponse', importResponse);

    await Task.delay(1000);

    this.setState({ importing: false });

    return true;
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    if (this.state.id === undefined) {
      return false;
    }

    if (this.newProduct.name === '' || this.state.id < 1) {
      return false;
    }

    if (this.newProduct.conditions.length < 1) {
      return false;
    }

    let productToAdd = new ExpertProduct(
      0,
      this.state.id,
      this.newProduct.name,
      this.newProduct.description,
      this.newProduct.notes,
    );

    // console.debug('\\SystemEdit\\formOnSubmit\\productToAdd', productToAdd);

    let apiResult = await Genius.Api.addProductWithConditions(
      productToAdd,
      this.newProduct.conditions,
    );

    if (apiResult < 1) {
      return false;
    }

    productToAdd.id = apiResult;

    let currentProducts = this.state.products;
    currentProducts?.push(productToAdd);

    let updatedRelations = await Genius.Api.getSystemRelations(this.state.id);

    this.setState({
      relations: updatedRelations,
      products: currentProducts,
    });

    // console.debug('\\SystemEdit\\formOnSubmit\\relations', this.state.relations);

    this.resetForm();

    ToastProvider.show(
      'New product added',
      'Product ' + productToAdd.name + ' added to ES.',
    );

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
    this.newProduct.conditions = selected;
  }

  private renderProductsTable(): JSX.Element {
    let products: IExpertProduct[] = this.state.products ?? [];

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
              <td className="-title">{singleProduct.name ?? ''}</td>
              <td className="-ellipsis">
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
                    (this.state.guid ?? '0') +
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
    if ((state.id ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <div className="row">
        <div className="col-12">
          <div className="-reveal">
            <span>System name</span>
            <h5 className="-font-secondary -fw-700">{state.name ?? ''}</h5>
          </div>
          <div className="-reveal">
            <span>Creation date:</span>
            <h5 className="-font-secondary -fw-700 -pb-3">
              {state.createdAt ?? '__unknown'}
            </h5>
          </div>

          <div className="-mb-3">
            <a
              href={'/api/export/' + state.guid ?? '#'}
              className="btn btn-dark btn-mobile -lg-mr-1 -btn-export">
              Export
            </a>
            <button
              className="btn btn-outline-dark btn-mobile -btn-import -lg-mr-1"
              onClick={e => this.importButtonOnClick(e)}>
              Import
            </button>
            <Link
              className="btn btn-outline-dark btn-mobile -btn-import -lg-mr-1"
              to={'/dashboard/sys/' + state.guid ?? '#'}>
              Run
            </Link>
            <Link to={'/dashboard/delete/' + state.guid ?? '#'}>
              Remove the expert system
            </Link>
          </div>
        </div>

        <div className="col-12 -mb-2">
          <hr />
        </div>

        <div className="col-12">
          <form method="POST" onSubmit={e => this.formOnSubmit(e)}>
            <h5 className="-font-secondary -fw-700 -pb-1">New product</h5>

            <div className="-reveal">
              <p>
                Product is the final result of the application's operation. If the purpose
                of the system is to select the best garden gnomes, the product can be -{' '}
                <i>"Red Gnome by iGnome INC"</i>.
              </p>
            </div>

            <div className="floating-input">
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

            <div className="floating-input">
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

            <div className="floating-input">
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
                  <br />
                  You can add multiple conditions by entering their names after a comma.
                  If any of the added conditions already exist, no copy will be made.
                </i>
              </small>
            </p>

            <ConditionsInput
              inputName="Conditions (confirming)"
              ref={element => {
                this.conditionsCloud = element;
              }}
              systemId={this.state.id ?? 0}
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
              <Link
                className="btn btn-outline-dark btn-mobile"
                to={'/dashboard/conditions/' + this.state.guid}>
                Edit all conditions
              </Link>
            </div>
          </form>
        </div>

        <div className="col-12">
          <hr />
        </div>

        <div className="col-12">
          <h5 className="-font-secondary -fw-700 -pb-1">Products</h5>
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
   * The main method responsible for refreshing and rendering the view.
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
              <h4 className="-font-secondary -fw-700 -pb-3">Edit</h4>
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

export default ORouter.bind(SystemEdit);
