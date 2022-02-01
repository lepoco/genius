/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Route, Routes } from 'react-router-dom';
import RoutedComponent from './common/RoutedComponent';
import withRouter from './common/withRouter';

import { Layout } from './components/Layout';
import { Home } from './components/Home';
import { Licenses } from './components/Licenses';
import { Legal } from './components/Legal';
import { Terms } from './components/Terms';
import { Privacy } from './components/Privacy';
import { SignIn } from './components/SignIn';

import { Main as Dashboard } from './components/dashboard/Main';
import { Account } from './components/dashboard/Account';
import { Statistics } from './components/dashboard/Statistics';
import { Users } from './components/dashboard/Users';
import { Password } from './components/dashboard/Password';
import { Settings } from './components/dashboard/Settings';
import System from './components/dashboard/System';
import SystemAdd from './components/dashboard/SystemAdd';
import SystemDelete from './components/dashboard/SystemDelete';
import SystemEdit from './components/dashboard/SystemEdit';

import { NotFound } from './components/NotFound';

class App extends RoutedComponent {
  public static displayName: string = App.name;

  public render(): JSX.Element {
    return (
      <Layout>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/licenses" element={<Licenses />} />
          <Route path="/legal" element={<Legal />} />
          <Route path="/terms" element={<Terms />} />
          <Route path="/privacy" element={<Privacy />} />
          <Route path="/signin" element={<SignIn />} />

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

export default withRouter(App);
