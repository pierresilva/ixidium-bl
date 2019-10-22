import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';

import {MzTabItemComponent} from './tab-item/index';
import {MzTabComponent} from './tab.component';
import {MzIconModule} from '../icon';
import {MzTabsDirective} from '@ngx-materialize/directives/mz-tabs.directive';

@NgModule({
  imports: [
    CommonModule,
    MzIconModule,
  ],
  declarations: [
    MzTabComponent,
    MzTabItemComponent,
    MzTabsDirective,
  ],
  exports: [
    MzTabComponent,
    MzTabItemComponent,
    MzTabsDirective,
  ],
})
export class MzTabModule {
}
