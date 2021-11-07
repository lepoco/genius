export const name = "FormHelpers";

/**
 * Triggers a set of automatic functions to facilitate the use of forms.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @module  Common/FormHelpers
 * @license GPL-3.0
 * @since   1.1.0
 */
export default class FormHelpers {
  static init() {
    return new FormHelpers();
  }

  constructor() {
    this.fillSelected();
  }

  fillSelected() {
    const SELECTS = document.querySelectorAll("select");

    if (!SELECTS) {
      return;
    }

    Array.prototype.forEach.call(SELECTS, (child: HTMLSelectElement) => {
      const DATASET = child.dataset;

      if (!DATASET.hasOwnProperty("selected")) {
        return;
      }

      const SELECTED_VALUE = DATASET.selected;

      Array.prototype.forEach.call(
        child.options,
        (option: HTMLOptionElement) => {
          if (SELECTED_VALUE == option.value) {
            option.selected = true;
          }
        }
      );
    });
  }
}
