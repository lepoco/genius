/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import ReactDOM from 'react-dom';
import { Suspense } from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import { Default } from './components/layouts/Default';

import Home from './components/pages/Home';
import Licenses from './components/pages/Licenses';
import Legal from './components/pages/Legal';
import Terms from './components/pages/Terms';
import Privacy from './components/pages/Privacy';
import SignIn from './components/pages/SignIn';

import Dashboard from './components/pages/dashboard/Main';
import Product from './components/pages/dashboard/Product';
import Condition from './components/pages/dashboard/Condition';
import Conditions from './components/pages/dashboard/Conditions';
import Account from './components/pages/dashboard/Account';
import Statistics from './components/pages/dashboard/Statistics';
import Users from './components/pages/dashboard/Users';
import Password from './components/pages/dashboard/Password';
import Settings from './components/pages/dashboard/Settings';
import System from './components/pages/dashboard/System';
import SystemAdd from './components/pages/dashboard/SystemAdd';
import SystemDelete from './components/pages/dashboard/SystemDelete';
import SystemEdit from './components/pages/dashboard/SystemEdit';
import NotFound from './components/pages/NotFound';

import * as serviceWorkerRegistration from './serviceWorkerRegistration';

import './styles/app.scss';

const baseUrl: string =
  document.getElementsByTagName('base')[0].getAttribute('href') ?? '';

ReactDOM.render(
  <BrowserRouter basename={baseUrl}>
    <Suspense fallback={<div className="container">Loading...</div>}>
      <Default>
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
          <Route path="/dashboard/product/:guid/:id" element={<Product />} />
          <Route path="/dashboard/condition/:guid/:id" element={<Condition />} />
          <Route path="/dashboard/conditions/:guid" element={<Conditions />} />
          <Route path="/dashboard/add" element={<SystemAdd />} />
          <Route path="/dashboard/edit/:guid" element={<SystemEdit />} />
          <Route path="/dashboard/delete/:guid" element={<SystemDelete />} />

          <Route path="*" element={<NotFound />} />
        </Routes>
      </Default>
    </Suspense>
  </BrowserRouter>,
  document.getElementById('root'),
);

serviceWorkerRegistration.register();
