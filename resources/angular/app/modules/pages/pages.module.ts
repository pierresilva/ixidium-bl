import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PagesRoutingModule } from './pages-routing.module';
import { PagesComponent } from './pages.component';
import { SharedModule } from '../../shared/shared.module';

// generated components imports here //

// generated services imports here //


@NgModule({
  imports: [
    CommonModule,
    PagesRoutingModule,
    SharedModule,
  ],
  exports: [
    PagesComponent,
    // generated components declarations here //
  ],
  declarations: [
      PagesComponent,
      // generated components declarations here //
  ],
  providers: [
    // generated services providers here //
  ],
  entryComponents: [
    PagesComponent,
    // generated components declarations classes here //
  ],
})
export class PagesModule { }
