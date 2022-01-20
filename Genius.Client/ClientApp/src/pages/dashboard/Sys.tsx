import { Component } from 'react';
import IProps from '../../interfaces/IProps';

export class Sys extends Component<IProps> {
  static displayName = Sys.name;

  conditionalQuestion: boolean = false;

  constructor(props: IProps) {
    super(props);

    this.state = {};

    this.handleSubmit = this.handleSubmit.bind(this);
  }

  async handleSubmit(event) {
    event.preventDefault();

    const formData = new FormData();

    await fetch('api/expert/system/response', {
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
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12 -mb-3">
            <h4 className="-font-secondary -fw-700 -reveal">__systemname</h4>
            <p className="-reveal">__Description</p>
          </div>

          <div className="col-12 -reveal">
            {this.conditionalQuestion ? (
              <h4 className="-font-secondary -fw-700 -pb-3">
                <span className="--current_condition -pattern">
                  question__pattern
                </span>
              </h4>
            ) : (
              <div>
                <p>
                  <strong>question__</strong>
                </p>
                <h4 className="-font-secondary -fw-700 -pb-3">
                  <span className="--current_condition">
                    non conditional question
                  </span>
                </h4>
              </div>
            )}
          </div>

          <div className="col-12 -reveal">
            <form id="answer" method="POST" onSubmit={this.handleSubmit}>
              <button
                type="submit"
                id="submit_yes"
                name="submit_yes"
                value="yes"
                className="-yes btn btn-dark btn-mobile -lg-mr-1">
                Yes
              </button>
              <button
                type="submit"
                id="submit_no"
                name="submit_no"
                value="no"
                className="-no btn btn-outline-dark btn-mobile -lg-mr-1">
                No
              </button>
              <button
                type="submit"
                id="submit_dontknow"
                name="submit_dontknow"
                value="dontknow"
                className="-dontknow btn btn-outline-dark btn-mobile">
                I do not know
              </button>
            </form>
          </div>
        </div>
      </div>
    );
  }
}
