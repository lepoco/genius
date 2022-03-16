/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Suspense } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import App from './App';
import * as serviceWorkerRegistration from './serviceWorkerRegistration';

import './styles/app.scss';

const baseUrl: string =
  document.getElementsByTagName('base')[0].getAttribute('href') ?? '';

ReactDOM.render(
  <Router basename={baseUrl}>
    <Suspense fallback={<div>Loading...</div>}>
      <App />
    </Suspense>
  </Router>,
  document.getElementById('root'),
);

serviceWorkerRegistration.register();
