/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Component } from 'react';
import { Link } from 'react-router-dom';
import IProps from '../../interfaces/IProps';

interface IAddExpertState {
  systemName?: string;
  systemDescription?: string;
  systemType?: string;
  systemQuestion?: string;
}

export class Add extends Component<IProps, IAddExpertState> {
  static displayName: string = Add.name;

  constructor(props: IProps) {
    super(props);

    this.state = {
      systemName: '',
      systemDescription: '',
      systemType: 'fuzzy',
      systemQuestion: 'Does your gnome have {condition}?',
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
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

    const formData = new FormData();

    formData.append('name', this.state.systemName ?? '');
    formData.append('description', this.state.systemDescription ?? '');
    formData.append('question', this.state.systemQuestion ?? '');
    formData.append('type', this.state.systemType ?? '');

    await fetch('api/expert/system', {
      method: 'POST',
      body: formData,
    })
      .then(response => response.text())
      .then(data => {
        console.debug('Respone', data);
        //this.setState({ text: data, loading: false });
      });
  }

  render() {
    return (
      <div className='dashboard container pt-5 pb-5'>
        <div className='row'>
          <div className='col-12'>
            <h4 className='-font-secondary -fw-700 -pb-3 -reveal'>Add</h4>
          </div>
          <div className='col-12'>
            <form id='addSystem' method='POST' onSubmit={this.handleSubmit}>
              <input type='hidden' name='nonce' readOnly value='addsystem' />

              <h5 className='-font-secondary -fw-700 -pb-1 -reveal'>
                New expert system
              </h5>

              <div className='floating-input -reveal'>
                <input
                  className='floating-input__field'
                  type='text'
                  placeholder='Name'
                  name='systemName'
                  value={this.state.systemName}
                  onChange={this.handleInputChange}
                />
                <label htmlFor='systemName'>Name</label>
              </div>

              <div className='floating-input -reveal'>
                <input
                  className='floating-input__field'
                  type='text'
                  placeholder='Description'
                  name='systemDescription'
                  value={this.state.systemDescription}
                  onChange={this.handleInputChange}
                />
                <label htmlFor='systemDescription'>Description</label>
              </div>

              <div className='floating-input -reveal'>
                <select
                  id='systemType'
                  data-selected='fuzzy'
                  className='floating-input__field'
                  name='systemType'
                  placeholder='Type'
                  value={this.state.systemQuestion}
                  onChange={this.handleInputChange}>
                  <option disabled value='relational'>
                    Relation based
                  </option>
                  <option value='fuzzy'>Fuzzy set method (weight)</option>
                </select>
                <label htmlFor='systemType'>Type</label>
              </div>

              <div className='-mb-2'>
                <hr />
              </div>

              <div className='floating-input -reveal'>
                <input
                  className='floating-input__field'
                  type='text'
                  placeholder='Primary question'
                  name='systemQuestion'
                  value={this.state.systemQuestion}
                  onChange={this.handleInputChange}
                />
                <label htmlFor='systemQuestion'>Primary question</label>
              </div>

              <div className='-mb-3 -reveal'>
                <i>
                  Main question is displayed during system running. Presents the
                  name of the condition inside the bracket
                  &#123;condition&#125;.
                </i>
                <br />
                <i>
                  If the question has no bracket pattern, condition will be
                  displayed below.
                </i>
              </div>

              <div className='-reveal -pb-1'>
                <button
                  type='submit'
                  className='btn btn-dark btn-mobile -lg-mr-1'>
                  Create
                </button>
                <Link to="/dashboard" className='btn btn-outline-dark btn-mobile'>Cancel</Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    );
  }
}
