import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SharedModule} from '../../shared/shared.module';

import {OnlineHelpRoutingModule} from './online-help-routing.module';
import {OnlineHelpsComponent} from './online-helps.component';
import {OnlineHelpsFormComponent} from './online-helps-form/online-helps-form.component';
import {OnlineHelpsListComponent} from './online-helps-list/online-helps-list.component';
import {DragulaModule} from 'ng2-dragula';
import {OnlineHelpOrderComponent} from './online-help-order/online-help-order.component';

@NgModule({
  imports: [
    CommonModule,
    OnlineHelpRoutingModule,
    SharedModule,
    DragulaModule
  ],
  declarations: [
    OnlineHelpsComponent,
    OnlineHelpsFormComponent,
    OnlineHelpsListComponent,
    OnlineHelpOrderComponent,
  ],
})
export class OnlineHelpModule {
}
