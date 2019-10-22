import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChangePasswordRoutingModule } from './change-password-routing.module';
import { ChangePasswordComponent } from './change-password.component';

@NgModule({
  imports: [
    CommonModule,
    ChangePasswordRoutingModule
  ],
  declarations: [ChangePasswordComponent]
})
export class ChangePasswordModule { }
