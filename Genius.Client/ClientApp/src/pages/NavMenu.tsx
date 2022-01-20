import React, { Component } from 'react'
import { Link } from 'react-router-dom'
export class NavMenu extends Component {
  static displayName = NavMenu.name

  // constructor (props) {
  //   super(props);

  //   this.toggleNavbar = this.toggleNavbar.bind(this);
  //   this.state = {
  //     collapsed: true
  //   };
  // }

  // toggleNavbar () {
  //   this.setState({
  //     collapsed: !this.state.collapsed
  //   });
  // }

  render() {
    return (
      <section className="navbar navbar-expand-lg navbar-light">
        <div className="container">
          <a className="navbar-brand" href="/">
            <p>Genius</p>
          </a>
          <button
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse" id="navbarSupportedContent">
            <ul className="navbar-nav me-auto mb-2 mb-lg-0"></ul>
            <div className="d-flex -lg-mr-2">
              <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                <li className="nav-item">
                  <a className="nav-link" href="/dashboard">
                    Dashboard
                  </a>
                </li>

                <li className="nav-item">
                  <a className="nav-link" href="/dashboard/add">
                    Add new
                  </a>
                </li>

                <li className="nav-item">
                  <a className="nav-link" href="/dashboard/account">
                    Account
                  </a>
                </li>

                <li className="nav-item">
                  <a className="nav-link" href="/dashboard/statistics">
                    Statistics
                  </a>
                </li>
                <li className="nav-item">
                  <a className="nav-link" href="/dashboard/users">
                    Users
                  </a>
                </li>
                <li className="nav-item">
                  <a className="nav-link" href="/dashboard/settings">
                    Settings
                  </a>
                </li>
              </ul>
            </div>
            <div className="d-flex">
              {/* <a href="/signin" className="btn btn-outline-dark btn-mobile -lg-mr-1" type="submit">Sign in</a>
              <a href="/register" className="btn btn-dark btn-mobile" type="submit">Register for free</a> */}
            </div>
          </div>
        </div>
      </section>
    )
  }
}
