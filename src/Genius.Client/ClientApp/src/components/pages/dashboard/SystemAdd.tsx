/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { Link } from 'react-router-dom';
import Task from '../../common/Task';
import Modal from '../../common/Modal';
import Loader from '../../common/Loader';
import { Genius, ExpertSystem, ImportRequest } from '../../../genius/Genius';

/**
 * Represents the variables contained in the component state.
 */
interface ISystemAddState {
  id: number;
  systemName: string;
  systemDescription: string;
  systemAuthor: string;
  systemSource: string;
  systemType: string;
  systemQuestion: string;
  importing: boolean;
}

export class SystemAdd extends ORouter.PureComponent<ISystemAddState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = SystemAdd.name;

  private importModal: Modal | null = null;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {
      id: 0,
      importing: false,
      systemName: '',
      systemDescription: '',
      systemAuthor: '',
      systemSource: '',
      systemType: 'conditional',
      systemQuestion: 'Does your gnome have {condition}?',
    };

    this.formOnSubmit = this.formOnSubmit.bind(this);
    this.inputOnChange = this.inputOnChange.bind(this);
    this.buttonImportOnClick = this.buttonImportOnClick.bind(this);
    this.importInputOnChange = this.importInputOnChange.bind(this);
  }

  private async inputOnChange(
    event: React.ChangeEvent<HTMLInputElement> | React.ChangeEvent<HTMLSelectElement>,
  ): Promise<boolean> {
    const target: HTMLInputElement | HTMLSelectElement = event.target;
    const name: string = target.name;
    let value: string | boolean = target.value;

    if (target instanceof HTMLInputElement && target.type === 'checkbox') {
      value = target.checked;
    }

    // @ts-ignore
    this.setState({
      [name]: value,
    });

    return true;
  }

  private async buttonImportOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
  ): Promise<boolean> {
    event.preventDefault();

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

    var importRequest = new ImportRequest(
      0,
      selectedFile,
      this.state.systemName,
      this.state.systemDescription,
      this.state.systemQuestion,
      this.state.systemAuthor,
      this.state.systemSource,
      this.state.systemType
      );

    const importResponse = await Genius.Api.createNewFromFile(
      importRequest,
    );

    console.log('\\SystemAdd\\importInputOnChange\\importResponse', importResponse);

    await Task.delay(1000);

    this.setState({ importing: false });

    
   if (!importResponse.success) {
     return false;
   }

   const system = await Genius.Api.getSystemById(
     importResponse.systemId ?? 0,
     false,
     false,
     false,
   );

   if (system.id < 1) return false;

   this.router.navigate('/dashboard/sys/' + system.guid);

   return true;
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    const EXPERT_SYSTEM = new ExpertSystem(
      0,
      '',
      '',
      this.state.systemName,
      this.state.systemDescription,
      this.state.systemAuthor,
      this.state.systemSource,
      256,
      this.state.systemType,
      this.state.systemQuestion,
    );

    let apiResult = await Genius.Api.addSystem(EXPERT_SYSTEM);

    if (apiResult) {
      this.router.navigate('/dashboard');
    }

    return true;
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
              <h4 className="-font-secondary -fw-700 -pb-3">Add</h4>
            </div>
            <div className="col-12">
              <form id="addSystem" method="POST" onSubmit={e => this.formOnSubmit(e)}>
                <input type="hidden" name="nonce" readOnly value="addsystem" />

                <h5 className="-font-secondary -fw-700 -pb-1">New expert system</h5>

                <div className="floating-input">
                  <input
                    className="floating-input__field"
                    type="text"
                    placeholder="Name"
                    name="systemName"
                    value={this.state.systemName}
                    onChange={e => this.inputOnChange(e)}
                  />
                  <label htmlFor="systemName">Name</label>
                </div>

                <div className="floating-input">
                  <input
                    className="floating-input__field"
                    type="text"
                    placeholder="Description"
                    name="systemDescription"
                    value={this.state.systemDescription}
                    onChange={e => this.inputOnChange(e)}
                  />
                  <label htmlFor="systemDescription">Description</label>
                </div>

                <div className="floating-input">
                  <input
                    className="floating-input__field"
                    type="text"
                    placeholder="Author"
                    name="systemAuthor"
                    value={this.state.systemAuthor}
                    onChange={e => this.inputOnChange(e)}
                  />
                  <label htmlFor="systemAuthor">Author</label>
                </div>

                <div className="floating-input">
                  <input
                    className="floating-input__field"
                    type="text"
                    placeholder="Data source"
                    name="systemSource"
                    value={this.state.systemSource}
                    onChange={e => this.inputOnChange(e)}
                  />
                  <label htmlFor="systemSource">Data source</label>
                </div>

                <div className="floating-input">
                  <select
                    id="systemType"
                    data-selected="fuzzy"
                    className="floating-input__field"
                    name="systemType"
                    placeholder="Type"
                    value={this.state.systemQuestion}
                    onChange={e => this.inputOnChange(e)}>
                    <option value="relational">Relation based</option>
                    <option disabled value="relational_non_confident">
                      Relation with confidence score
                    </option>
                    <option disabled value="fuzzy">
                      Fuzzy set method (weight)
                    </option>
                    <option disabled value="fuzy_multi_value">
                      Fuzzy set method and multi value
                    </option>
                  </select>
                  <label htmlFor="systemType">Type</label>
                </div>

                <div className="-mb-2">
                  <hr />
                </div>

                <div className="floating-input">
                  <input
                    className="floating-input__field"
                    type="text"
                    placeholder="Primary question"
                    name="systemQuestion"
                    value={this.state.systemQuestion}
                    onChange={e => this.inputOnChange(e)}
                  />
                  <label htmlFor="systemQuestion">Primary question</label>
                </div>

                <div className="-mb-3">
                  <i>
                    Main question is displayed during system running. Presents the name of
                    the condition inside the bracket &#123;condition&#125;.
                  </i>
                  <br />
                  <i>
                    If the question has no bracket pattern, condition will be displayed
                    below.
                  </i>
                </div>

                <div className="-reveal -pb-1">
                  <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
                    Create
                  </button>
                  <Link
                    to="/dashboard"
                    className="btn btn-outline-dark btn-mobile -lg-mr-1">
                    Cancel
                  </Link>
                  <button
                    type="button"
                    className="btn btn-outline-dark btn-mobile"
                    onClick={e => this.buttonImportOnClick(e)}>
                    Import from file
                  </button>
                </div>
              </form>
            </div>
          </div>
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
                  Based on the contents of the selected file, a new expert system is
                  created. The importer will try to correlate the data automatically from
                  scratch.
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

export default ORouter.bind(SystemAdd);
