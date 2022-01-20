import React, { Component } from 'react';
import { Route, Routes } from 'react-router-dom';
//import { Dropdown as BootstrapDropdown } from "bootstrap";

import { Layout } from './pages/Layout';
import { Home } from './pages/Home';
import { Licenses } from './pages/Licenses';
import { Legal } from './pages/Legal';
import { Terms } from './pages/Terms';
import { Privacy } from './pages/Privacy';
import { SignIn } from './pages/SignIn';
import { Counter } from './pages/Counter';

import { Main as Dashboard } from './pages/dashboard/Main';
import { Account } from './pages/dashboard/Account';
import { Statistics } from './pages/dashboard/Statistics';
import { Users } from './pages/dashboard/Users';
import { Password } from './pages/dashboard/Password';
import { Settings } from './pages/dashboard/Settings';
import { Sys as System } from './pages/dashboard/Sys';
import { Add as SystemAdd } from './pages/dashboard/Add';
import { Delete as SystemDelete } from './pages/dashboard/Delete';
import { Edit as SystemEdit } from './pages/dashboard/Edit';

import { NotFound } from './pages/NotFound';

// window.onload = function (e) {
//   new BootstrapDropdown();
// };

export default class App extends Component {
  static displayName = App.name;

  // constructor(props) {
  //   super(props);
  // }

  render() {
    return (
      <Layout>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/licenses" element={<Licenses />} />
          <Route path="/legal" element={<Legal />} />
          <Route path="/terms" element={<Terms />} />
          <Route path="/privacy" element={<Privacy />} />
          <Route path="/signin" element={<SignIn />} />
          <Route path="/counter" element={<Counter />} />

          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/dashboard/account" element={<Account />} />
          <Route path="/dashboard/statistics" element={<Statistics />} />
          <Route path="/dashboard/users" element={<Users />} />
          <Route path="/dashboard/password" element={<Password />} />
          <Route path="/dashboard/settings" element={<Settings />} />

          <Route path="/dashboard/sys/:guid" element={<System />} />
          <Route path="/dashboard/add" element={<SystemAdd />} />
          <Route path="/dashboard/edit/:guid" element={<SystemEdit />} />
          <Route path="/dashboard/delete/:guid" element={<SystemDelete />} />

          <Route path="*" element={<NotFound />} />
        </Routes>
      </Layout>
    );
  }
}
