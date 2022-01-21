/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import React, { Component } from 'react';

export class Account extends Component {
  static displayName = Account.name;

  render () {
    return (
      <div className="dashboard container -mt-5 -mb-5">
        <div className="row">
          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Account</h4>
          </div>
          <div className="col-12 dashboard__section">
            <div className="dashboard__banner h-100 p-5 bg-light -rounded-2 -reveal">
              <div className="dashboard__banner__picture">
                <img className="editable__picture" src="img/pexels-photo-8386434.jpeg" alt="Stack Overflow logo and icons and such"/>
              </div>
              <div>
                <h4>Hello, <span className="editable__displayname">Username</span></h4>
                <p>user@example.com</p>
              </div>
            </div>
          </div>
          <div className="col-12 -mt-5">
            <form id="account" method="POST">
              <input type="hidden" name="action" value="Account" />
              <input type="hidden" name="nonce" value="account_nonce" />
              <input type="hidden" name="id" value="user_id" />

              <div className="floating-input -reveal">
                <input disabled={true} className="floating-input__field -keep-disabled" type="text" name="email"
                  placeholder="Email" value="user@example.com"/>
                <label htmlFor="email">Email</label>
              </div>

              <div className="floating-input -reveal">
                <input className="floating-input__field" type="text" placeholder="Display name"
                  value="" name="displayname"/>
                <label htmlFor="displayname">Display name</label>
              </div>

              <div className="floating-input -reveal">
                <select className="floating-input__field" placeholder="Language"
                  data-selected="en_US" name="language">
                  <option value="en_US">English</option>
                  <option value="pl_PL">Polish</option>
                </select>
                <label htmlFor="language">Language</label>
              </div>

              <div className="floating-input -reveal">
                <input className="floating-input__field" type="file" placeholder="Profile picture" value=""
                  name="picture"/>
                <label htmlFor="picture">Profile picture</label>
              </div>

              <div className="-pb-1 -reveal">
                <button type="submit" className="btn btn-dark btn-mobile -lg-mr-1">Update</button>
                <a href="dashboard/password" className="btn btn-outline-dark btn-mobile">Change your password</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    );
  }
}
