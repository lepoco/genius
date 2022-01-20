
import React, { Component } from 'react';


export class Legal extends Component {
  static displayName = Legal.name;

  render () {
    return (
      <div className="container -pt-5">
        <div className="row">

          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Legal Agreements</h4>
          </div>

          <div className="col-12 -mb-5">
            <p className="-reveal">
              Genius is not a real website. It's just a college project. Don't use it.
            </p>
          </div>
        </div>
      </div>
    );
  }
}

