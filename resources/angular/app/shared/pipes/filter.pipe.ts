import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'filter'
})
export class FilterPipe implements PipeTransform {
  transform(items: any[], searchText: string, index: string = null): any[] {
    if (!items) {
      return [];
    }
    if (!searchText) {
      return items;
    }
    searchText = searchText.toLowerCase();
    if (index) {
      return items.filter( it => {
        return it[index].toLowerCase().includes(searchText);
      });
    } else {
      return items.filter( it => {
        return it.toLowerCase().includes(searchText);
      });
    }

  }
}
