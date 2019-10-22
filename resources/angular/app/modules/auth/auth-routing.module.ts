import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {AuthLoginComponent} from './auth-login/auth-login.component';
import { AuthLogoutComponent } from './auth-logout/auth-logout.component';

const routes: Routes = [
  {
    path: '',
    component: AuthLoginComponent,
    data: {
      title: 'Login',
      logged: false,

    },
  },
  {
    path: 'logout',
    component: AuthLogoutComponent,
    data: {
      title: 'Salir',
      logged: true,

    }
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routes)
  ],
  exports: [
    RouterModule
  ]
})
export class AuthRoutingModule {
}
