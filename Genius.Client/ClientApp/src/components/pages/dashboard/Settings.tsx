/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { ORouter } from '../../../common/ORouter';
import { ToastProvider } from '../../common/Toasts';

/**
 * Represents the variables contained in the Component state.
 */
interface ISettingsState {}

/**
 * Dashboard - Settings page Component.
 */
export class Settings extends ORouter.PureComponent<ISettingsState> {
  /**
   * The display name of the Component.
   */
  public static displayName: string = Settings.name;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: ORouter.IRouterProps) {
    super(props);

    this.state = {};

    this.formOnSubmit = this.formOnSubmit.bind(this);
  }

  private async formOnSubmit(event: React.FormEvent<HTMLFormElement>): Promise<boolean> {
    event.preventDefault();

    ToastProvider.show('Error', 'Settings update is not possible.');

    return false;
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <div className="dashboard container -mt-5 -mb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3">Settings</h4>
          </div>
          <div className="col-12 -mt-5">
            <form method="POST" onSubmit={e => this.formOnSubmit(e)}>
              <div className="floating-input">
                <input
                  disabled={true}
                  className="floating-input__field -keep-disabled"
                  type="text"
                  name="email"
                  placeholder="Email"
                  value="user@example.com"
                />
                <label htmlFor="email">Email</label>
              </div>

              <div className="-pb-1">
                <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">
                  Update
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    );
  }
}

export default ORouter.bind(Settings);
