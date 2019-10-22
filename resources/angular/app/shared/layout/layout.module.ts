import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HeaderComponent} from './header/header.component';
import {FooterComponent} from './footer/footer.component';
import {NavComponent} from './nav/nav.component';
import {SideNavLeftComponent} from './side-nav-left/side-nav-left.component';
import {SideNavRightComponent} from './side-nav-right/side-nav-right.component';
import {LoadingHttpComponent} from './loading-http/loading-http.component';
import {LoadingComponent} from './loading/loading.component';
import {SharedModule} from '../shared.module';
import {NgxMaterializeModule} from '@ngx-materialize/ngx-materialize.module';

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    NgxMaterializeModule,
  ],
  declarations: [
    HeaderComponent,
    FooterComponent,
    NavComponent,
    SideNavLeftComponent,
    SideNavRightComponent,
    LoadingHttpComponent,
    LoadingComponent,
  ],
  exports: [
    HeaderComponent,
    FooterComponent,
    NavComponent,
    SideNavLeftComponent,
    SideNavRightComponent,
    LoadingHttpComponent,
    LoadingComponent,
  ],
  entryComponents: [
    HeaderComponent,
    FooterComponent,
    NavComponent,
    SideNavLeftComponent,
    SideNavRightComponent,
    LoadingHttpComponent,
    LoadingComponent,
  ]
})
export class LayoutModule {
}
