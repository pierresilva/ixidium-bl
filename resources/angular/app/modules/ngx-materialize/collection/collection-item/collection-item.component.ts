import { Component, HostBinding, Input } from '@angular/core';

@Component({
  selector: 'mz-collection-item',
  templateUrl: './collection-item.component.html',
  styleUrls: ['./collection-item.component.scss'],
})
export class MzCollectionItemComponent {
  @HostBinding('class.collection-item') true;
  @HostBinding('class.avatar') @Input() avatar: boolean;
  @HostBinding('class.dismissable') @Input() dismissable: boolean;
}
