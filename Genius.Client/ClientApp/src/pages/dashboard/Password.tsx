import React, { Component } from 'react';

export class Password extends Component {
  static displayName = Password.name;

  render () {
    return (
      <div className="container -pt-5">
        <div className="row">
            <div className="col-12 col-lg-6 -pb-3 -mh-70 -flex-center -reveal">
                <div>
                    <h2 className="-font-secondary -fw-700">Password</h2>
                    <p>Genius is a tool for creating expert systems.</p>
                </div>
            </div>
            <div className="col-12 col-lg-6 -flex-center -reveal">
                <img src="img/mind-circuit.png" alt="Genius front page" style={{width: "100%"}} />
            </div>
        </div>
    </div>
    );
  }
}
