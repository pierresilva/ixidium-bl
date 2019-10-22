import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {CapitalizePipe} from './capitalize.pipe';
import {MessagePipe} from './message.pipe';
import {EscapeHtmlPipe} from './keep-html.pipe';
import {FilterPipe} from './filter.pipe';
import {EllipsisPipe} from './ellipsis.pipe';

@NgModule({
  imports: [
    CommonModule,
  ],
  declarations: [
    CapitalizePipe,
    MessagePipe,
    EscapeHtmlPipe,
    FilterPipe,
    EllipsisPipe,
  ],
  exports: [
    MessagePipe,
    CapitalizePipe,
    EscapeHtmlPipe,
    FilterPipe,
    EllipsisPipe,
  ]
})
export class PipesModule {
}
