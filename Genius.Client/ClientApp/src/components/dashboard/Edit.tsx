import React, { Component } from 'react';
import withRouter from './../../common/withRouter';
import IRouterProps from './../../interfaces/IRouterProps';
import IRouter from './../../interfaces/IRouter';
import { Link } from 'react-router-dom';

interface IEditExpertState {
  systemLoaded?: boolean;
  systemId?: number;
  systemGuid?: string;
  systemVersion?: string;
  systemName?: string;
  systemDescription?: string;
  systemType?: string;
  systemQuestion?: string;
}

class Edit extends Component<IRouterProps, IEditExpertState> {
  static displayName = Edit.name;

  router: IRouter;

  constructor(props: IRouterProps) {
    super(props);

    this.router = props.router;

    this.state = {
      systemLoaded: false,
      systemId: 0,
      systemGuid: '',
      systemVersion: '',
      systemName: '',
      systemDescription: '',
      systemType: '',
      systemQuestion: '',
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount() {
    this.populateExpertSystemData();
  }

  async populateExpertSystemData() {
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
      systemLoaded: true,
    });
  }

  handleInputChange(event) {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  async handleSubmit(event) {
    event.preventDefault();

    // const formData = new FormData();

    // formData.append('name', this.state.systemName ?? '');
    // formData.append('description', this.state.systemDescription ?? '');
    // formData.append('question', this.state.systemQuestion ?? '');
    // formData.append('type', this.state.systemType ?? '');

    // await fetch('api/expert/system', {
    //   method: 'UPDATE',
    //   body: formData,
    // })
    //   .then(response => response.text())
    //   .then(data => {

    //     //this.setState({ text: data, loading: false });
    //   });
  }

  static renderSystemView(state: IEditExpertState) {
    if ((state.systemId ?? 0) < 1) {
      return <p>No systems found</p>;
    }

    return (
      <div className="row">
        <div className="col-12">
          <div className="-mb-3 -reveal">
            <button className="btn btn-dark btn-mobile -lg-mr-1 -btn-add-product">
              Add product
            </button>
            <button className="btn btn-outline-dark btn-mobile -lg-mr-1 -btn-add-condition">
              Add condition
            </button>
            <button className="btn btn-outline-dark btn-mobile -btn-add-relation -lg-mr-1">
              Add relation
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
          <form id="addProduct" method="POST">
            <h5 className="-font-secondary -fw-700 -pb-1 -reveal">
              New product
            </h5>

            <div className="-reveal">
              <p>
                Product is the final result of the application\'s operation. If
                the purpose of the system is to select the best garden gnomes,
                the product can be - Red Gnome by iGnome INC.
              </p>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Name"
                name="product_name"
              />
              <label htmlFor="product_name">Name</label>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Description"
                name="product_description"
              />
              <label htmlFor="product_description">Description</label>
            </div>

            <div className="-reveal -pb-2">
              <button
                type="submit"
                className="btn btn-dark btn-mobile -lg-mr-1">
                Add
              </button>
            </div>
          </form>
        </div>

        <div className="col-12 -mb-2">
          <hr />
        </div>

        <div className="col-12">
          <form id="addCondition" method="POST">
            <h5 className="-font-secondary -fw-700 -pb-1 -reveal">
              New condition
            </h5>

            <div className="-reveal">
              <p>
                Condition is anything that meets or contradicts the presence of
                the product. For example, if the condition is "Is the gnome
                green?" It will exclude all gnomes that are red.
              </p>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Name"
                name="condition_name"
              />
              <label htmlFor="condition_name">Name</label>
            </div>

            <div className="floating-input -reveal">
              <input
                className="floating-input__field"
                type="text"
                placeholder="Description"
                name="condition_description"
              />
              <label htmlFor="condition_description">Description</label>
            </div>

            <div className="-reveal -pb-2">
              <button
                type="submit"
                className="btn btn-dark btn-mobile -lg-mr-1">
                Add
              </button>
            </div>
          </form>
        </div>

        <div className="col-12 -mb-2">
          <hr />
        </div>

        <div className="col-12">
          <form id="addRelation" method="POST">
            <input type="hidden" name="action" value="AddRelation" />
            <input type="hidden" name="nonce" value="@nonce('addrelation" />
            <input
              type="hidden"
              name="system_id"
              value="{{ $system->getId() ?? '0' }}"
            />
            <input
              type="hidden"
              name="system_uuid"
              value="{{ $system->getUUID() ?? '' }}"
            />

            <h5 className="-font-secondary -fw-700 -pb-1 -reveal">
              New relation
            </h5>

            <div className="-reveal">
              <p>
                Relation explains the connection between the condition and the
                product.
              </p>
            </div>

            {/* <div className="floating-input -reveal">
                <select id="product_id" data-selected="{{ $selected ?? $value ?? '' }}" className="floating-input__field"
                  name="product_id" placeholder="Product">
                  <option value="" selected disabled hidden>Choose product here...</option>

                  @foreach($products as $product)
                  <option value="{{ $product->getId() ?? ' '}}">{{ $product->getName() ?? ' '}}</option>
                  @endforeach

                </select>
                <label htmlFor="product_id">Product</label>
              </div> */}

            <div className="floating-input -reveal">
              <select
                id="relation_id"
                data-selected="{{ $selected ?? $value ?? '' }}"
                className="floating-input__field"
                name="relation_id"
                placeholder="Relation">
                <option value="1">Condition belongs to the product</option>
                <option value="1">Condition contradicts the product</option>
                <option value="1">Condition is inert to the product</option>
              </select>
              <label htmlFor="relation_id">Relation</label>
            </div>

            {/* <div className="floating-input -reveal">
                <select id="condition_id" data-selected="{{ $selected ?? $value ?? '' }}" className="floating-input__field"
                  name="condition_id" placeholder="Condition">
                  <option value="" selected disabled hidden>Choose condition here...</option>

                  @foreach($conditions as $condition)
                  <option value="{{ $condition->getId() ?? ' '}}">{{ $condition->getName() ?? ' '}}</option>
                  @endforeach

                </select>
                <label htmlFor="condition_id">Condition</label>
              </div> */}

            <div className="-reveal -pb-2">
              <button
                type="submit"
                className="btn btn-dark btn-mobile -lg-mr-1">
                Add
              </button>
            </div>
          </form>
        </div>
      </div>
    );
  }

  render() {
    let contents = !this.state.systemLoaded ? (
      <p>
        <em>Loading...</em>
      </p>
    ) : (
      Edit.renderSystemView(this.state)
    );

    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Edit</h4>
          </div>
        </div>

        {contents}
      </div>
    );
  }
}

export default withRouter(Edit);
