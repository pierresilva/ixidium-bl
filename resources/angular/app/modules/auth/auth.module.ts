import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AuthRoutingModule} from './auth-routing.module';
import {AuthLoginComponent} from './auth-login/auth-login.component';
import {AuthLogoutComponent} from './auth-logout/auth-logout.component';
import {SharedModule} from '../../shared/shared.module';
import {AuthComponent} from './auth.component';
import {AuthChangePasswordComponent} from './auth-change-password/auth-change-password.component';
import {AuthRecoverPasswordComponent} from './auth-recover-password/auth-recover-password.component';
import {AuthRegisterComponent} from './auth-register/auth-register.component';

@NgModule({
  imports: [
    CommonModule,
    AuthRoutingModule,
    SharedModule
  ],
  declarations: [
    AuthLoginComponent,
    AuthLogoutComponent,
    AuthComponent,
    AuthChangePasswordComponent,
    AuthRecoverPasswordComponent,
    AuthRegisterComponent,
  ],
  exports: [
    AuthLoginComponent,
    AuthLogoutComponent,
    AuthComponent,
    AuthChangePasswordComponent,
    AuthRecoverPasswordComponent,
    AuthRegisterComponent,
  ]
})
export class AuthModule {
}
