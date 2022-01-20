import React, { Component } from 'react';
import { Route, Routes } from 'react-router-dom';
import IRouterProps from './interfaces/IRouterProps';
import IRouter from './interfaces/IRouter';
import withRouter from './common/withRouter';

import { Layout } from './components/Layout';
import { Home } from './components/Home';
import { Licenses } from './components/Licenses';
import { Legal } from './components/Legal';
import { Terms } from './components/Terms';
import { Privacy } from './components/Privacy';
import { SignIn } from './components/SignIn';
import { Counter } from './components/Counter';

import { Main as Dashboard } from './components/dashboard/Main';
import { Account } from './components/dashboard/Account';
import { Statistics } from './components/dashboard/Statistics';
import { Users } from './components/dashboard/Users';
import { Password } from './components/dashboard/Password';
import { Settings } from './components/dashboard/Settings';
import { Sys as System } from './components/dashboard/Sys';
import { Add as SystemAdd } from './components/dashboard/Add';
import { Delete as SystemDelete } from './components/dashboard/Delete';
import Edit from './components/dashboard/Edit';

import { NotFound } from './components/NotFound';

class App extends Component<IRouterProps> {
  static displayName = App.name;

  router: IRouter;

  constructor(props: IRouterProps) {
    super(props);

    this.router = props.router;
  }

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
          <Route path="/dashboard/edit/:guid" element={<Edit />} />
          <Route path="/dashboard/delete/:guid" element={<SystemDelete />} />

          <Route path="*" element={<NotFound />} />
        </Routes>
      </Layout>
    );
  }
}

export default withRouter(App);
