import React, { Component } from 'react';


export class NotFound extends Component {
  static displayName = NotFound.name;

  render () {
    return (
      <div className="container -pt-5">
          <div className="row">
              <div className="col-12 col-lg-6 -pb-3 -mh-70 -flex-center -reveal">
                  <div>
                      <h2 className="-font-secondary -fw-700">Page not found</h2>
                      <p>We can't find the page you are looking for.</p>
                  </div>
              </div>
          </div>
      </div>
    );
  }
}
