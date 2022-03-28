/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { useRef } from 'react';
import type { BrowserHistory } from 'history';
import { createBrowserHistory } from 'history';
import { useLocation, useNavigate, useParams } from 'react-router-dom';

/**
 * Ugly static way to make React component classes variable from a router.
 * @param Component Instance of React component.
 * @returns JSX.Element with DOM Router parameters applied.
 */
export default function withRouter(Component: any) {
  return props => (
    <Component
      {...props}
      router={{
        location: useLocation(),
        navigate: useNavigate(),
        params: useParams(),
        navigator: useRef<BrowserHistory>()?.current ?? createBrowserHistory({ window }),
      }}
    />
  );
}
