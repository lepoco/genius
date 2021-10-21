export const name = "Forms";

export default class Forms {
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
