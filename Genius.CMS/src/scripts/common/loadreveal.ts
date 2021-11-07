import * as ScrollReveal from "./../components/scrollreveal";

export const name = "LoadReveal";

/**
 * Animates pages when opened.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Common/LoadReveal
 * @license GPL-3.0
 * @since   1.1.0
 */
export default class LoadReveal {
  static init() {
    ScrollReveal().reveal(".-reveal", {
      //delay: 150,
      duration: 400,
      interval: 16,
      reset: false,
      scale: .95,
      cleanup: false,
      origin: 'bottom',
      easing: 'cubic-bezier(0.5, 0, 0, 1)'
    });
  }
}
