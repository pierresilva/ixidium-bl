import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {OnlineHelpsComponent} from './online-helps.component';

const routes: Routes = [
  {
    path: '',
    component: OnlineHelpsComponent,
    data: {
      'title': 'Manual Online',
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class OnlineHelpRoutingModule {
}
