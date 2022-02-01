/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import {
  useLocation,
  useNavigate,
  useParams,
  Location,
  NavigateFunction,
  Params,
} from 'react-router-dom';

/**
 * Ugly static way to make React component classes variable from a router.
 * @param Component Instance of React component.
 * @returns JSX.Element with DOM Router parameters applied.
 */
export default function withRouter(
  ReactComponent: any,
): (props: any) => JSX.Element {
  function AddComponentProps(props: any): JSX.Element {
    const DOM_ROUTER_LOCATION: Location = useLocation();
    const DOM_ROUTER_NAVIGATE: NavigateFunction = useNavigate();
    const DOM_ROUTER_PARAMS: Readonly<Params<any>> = useParams();

    return (
      <ReactComponent
        {...props}
        router={{
          location: DOM_ROUTER_LOCATION,
          navigate: DOM_ROUTER_NAVIGATE,
          params: DOM_ROUTER_PARAMS,
        }}
      />
    );
  }

  return AddComponentProps;
}
