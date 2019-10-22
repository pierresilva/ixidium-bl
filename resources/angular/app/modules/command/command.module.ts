import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CommandRoutingModule } from './command-routing.module';
import { CommandComponent } from './command.component';
import {SharedModule} from '../../shared/shared.module';

@NgModule({
  declarations: [CommandComponent],
  imports: [
    CommonModule,
    CommandRoutingModule,
    SharedModule,
  ]
})
export class CommandModule { }
