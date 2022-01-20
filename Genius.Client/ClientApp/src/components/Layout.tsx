import React, { Component } from 'react';
import { NavMenu } from './NavMenu';
import { Footer } from './Footer';

export class Layout extends Component {
  static displayName = Layout.name;

  render () {
    return (
      <div id="app">
        <NavMenu />
        <div className="container">
          {this.props.children}
        </div>
        <Footer/>
      </div>
    );
  }
}
