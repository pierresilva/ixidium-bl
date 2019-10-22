/**
 * Simple array helpers
 */
export class HelperArray {

  /**
   * Find value in array by key
   *
   * @param array
   * @param key
   * @param value
   * @param ret
   * @returns {any}
   */
  find(array, key, value, ret) {

    for (let i = 0; i < array.length; i++) {

      if (array[i][key] === value) {
        return array[i][ret];
      }

      if (array[i][key].isArray()) {
        return this.find(array[i][key], key, value, ret);
      }
    }

    return null;
  }

  sortByKey(array, key) {
    return array.sort(function(a, b) {
      let x = a[key];
      let y = b[key];

      if (typeof x === 'string') {
        x = ('' + x).toLowerCase();
      }
      if (typeof y === 'string') {
        y = ('' + y).toLowerCase();
      }

      return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
  }
}
