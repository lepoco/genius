/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

// In production, we register a service worker to serve assets from local cache.

// This lets the app load faster on subsequent visits in production, and gives
// it offline capabilities. However, it also means that developers (and users)
// will only see deployed updates on the "N+1" visit to a page, since previously
// cached resources are updated in the background.

// To learn more about the benefits of this model, read https://goo.gl/KwvDNy.
// This link also includes instructions on opting out of this behavior.

/**
 * Class responsible for registering the service worker.
 */
export default class ServiceWorkerRegistrar {
  public isLocalhost: boolean = false;

  public publicUrl: URL;

  public constructor() {
    this.verifyLocalhost();

    // The URL constructor is available in all browsers that support SW.
    this.publicUrl = new URL(
      process.env.PUBLIC_URL ?? '',
      window.location as any,
    );

    console.debug('\\ServiceWorker\\isLocalhost', this.isLocalhost);
    console.debug('\\ServiceWorker\\publicUrl', this.publicUrl);
  }

  public register(allowNotInProduction: boolean = false): void {
    if (process.env.NODE_ENV !== 'production' && !allowNotInProduction) {
      console.warn(
        '\\ServiceWorker\\register',
        'Service worker not in production mode.',
      );

      return;
    }

    if (!('serviceWorker' in navigator)) {
      console.warn(
        '\\ServiceWorker\\register',
        'Service worker not in navigator.',
      );

      return;
    }

    if (this.publicUrl.origin !== window.location.origin) {
      // Our service worker won't work if PUBLIC_URL is on a different origin
      // from what our page is served on. This might happen if a CDN is used to
      // serve assets; see https://github.com/facebookincubator/create-react-app/issues/2374

      console.warn(
        '\\ServiceWorker\\register',
        "Service worker won't work if PUBLIC_URL is on a different origin",
      );
      return;
    }

    window.addEventListener('load', () => {
      const swUrl = `${this.publicUrl.origin}/service-worker.js`;

      if (this.isLocalhost) {
        // This is running on localhost. Lets check if a service worker still exists or not.
        this.checkValidServiceWorker(swUrl);
      } else {
        // Is not local host. Just register service worker
        this.registerValidSW(swUrl);
      }
    });
  }

  public unregister(): void {
    if (!('serviceWorker' in navigator)) {
      return;
    }

    navigator.serviceWorker.ready.then(registration => {
      registration.unregister();
    });
  }

  private verifyLocalhost(): boolean {
    this.isLocalhost = Boolean(
      window.location.hostname === 'localhost' ||
        // [::1] is the IPv6 localhost address.
        window.location.hostname === '[::1]' ||
        // 127.0.0.1/8 is considered localhost for IPv4.
        window.location.hostname.match(
          /^127(?:\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/,
        ),
    );

    return this.isLocalhost;
  }

  private registerValidSW(swUrl: string): void {
    navigator.serviceWorker
      .register(swUrl)
      .then(registration => {
        registration.onupdatefound = () => {
          const installingWorker = registration.installing;

          if (installingWorker == null) {
            return;
          }

          installingWorker.onstatechange = () => {
            if (installingWorker.state === 'installed') {
              if (navigator.serviceWorker.controller) {
                // At this point, the old content will have been purged and
                // the fresh content will have been added to the cache.
                // It's the perfect time to display a "New content is
                // available; please refresh." message in your web app.
                console.log('New content is available; please refresh.');
              } else {
                // At this point, everything has been precached.
                // It's the perfect time to display a
                // "Content is cached for offline use." message.
                console.log('Content is cached for offline use.');
              }
            }
          };
        };
      })
      .catch(error => {
        console.error('Error during service worker registration:', error);
      });
  }

  private checkValidServiceWorker(swUrl: string): void {
    // Check if the service worker can be found. If it can't reload the page.
    fetch(swUrl)
      .then(response => {
        if (response.headers.get('content-type') == null) {
          return;
        }

        // Ensure service worker exists, and that we really are getting a JS file.
        if (
          response.status === 404 ||
          (response.headers.get('content-type') ?? '').indexOf('javascript') ===
            -1
        ) {
          // No service worker found. Probably a different app. Reload the page.
          navigator.serviceWorker.ready.then(registration => {
            registration.unregister().then(() => {
              window.location.reload();
            });
          });
        } else {
          // Service worker found. Proceed as normal.
          this.registerValidSW(swUrl);
        }
      })
      .catch(() => {
        console.log(
          'No internet connection found. App is running in offline mode.',
        );
      });
  }
}
