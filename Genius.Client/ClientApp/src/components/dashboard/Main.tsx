import React, { Component } from 'react';

export class Main extends Component {
  static displayName = Main.name;

  isLoading:boolean = true;

  expertSystems:object = {};

  constructor(props) {
    super(props);

    this.isLoading = true;
    this.state = { forecasts: [], loading: true };
  }

  componentDidMount() {
    this.populateExpertSystemsData();
  }

  static renderSystemsList(systems) {
    
    if (Object.keys(systems).length < 1) {
      return (
        <p>No systems found</p>
      );
    }
    
    return (
      <div className="row">
        {systems.map(system =>

        <div className="col-12 dashboard__section -mb-3 -reveal">
        <div className="dashboard__banner h-100 p-5 bg-light -rounded-2">
          <div>
            <h4>{system.name ?? '' }</h4>
            <p>{system.description ?? ''}</p>
            <a href={ '/dashboard/sys/' + system.guid ?? '#' } className="btn btn-outline-dark btn-mobile -lg-mr-1" type="button">Run</a>
            <a href={ '/dashboard/edit/' + system.guid ?? '#' } className="btn btn-dark btn-mobile -lg-mr-1" type="button">Edit</a>
          </div>
        </div>
      </div>
      )}
      </div>
    );
  }

  render() {
    let contents = this.isLoading
      ? <p><em>Loading...</em></p>
      : Main.renderSystemsList(this.expertSystems);

    return (
      <div className="dashboard container pt-5 pb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Dashboard</h4>
          </div>
        </div>

        {contents}

      </div>
    );
  }

  async populateExpertSystemsData() {
    const response = await fetch('api/expert/system');
    const data = await response.json();

    console.debug("api/expert/system", data);

    this.isLoading = false;
    this.expertSystems = data;

    this.setState({ expertSystems: data, loading: false });
  }
}
