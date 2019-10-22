import {BrowserModule} from '@angular/platform-browser';
import {LOCALE_ID, APP_INITIALIZER, ErrorHandler, NgModule} from '@angular/core';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {MalihuScrollbarModule} from 'ngx-malihu-scrollbar';
import {MarkdownModule} from 'ngx-markdown';
import {AppRoutingModule} from './app-routing.module';
import {ServiceWorkerModule} from '@angular/service-worker';
import {AppComponent} from './app.component';
import {environment} from '../environments/environment';
import {SharedModule} from './shared/shared.module';
import {LayoutModule} from './shared/layout/layout.module';
import {PreloadAllModules, RouterModule} from '@angular/router';
import {ROUTES} from './app-routing';
import {LoadingHttpService} from './shared/services/loading-http.service';
import {AlertService} from './shared/services/alert.service';
import {AlertServiceComponent} from './shared/services/alert-service/alert-service.component';
import {ApiService} from './shared/services/api.service';
import {AppErrorHandler} from './shared/services/app-error-handler.service';
import {ErrorService} from './shared/services/error.service';
import {JwtService} from './shared/services/jwt.service';
import {MessageService} from './shared/services/message.service';
import {AuthGuard} from './shared/guards/auth.guard';
import {AuthService} from './shared/services/auth.service';
import {MessagesLoader} from './shared/clasess/messages-loader';
import {HTTP_INTERCEPTORS} from '@angular/common/http';
import {LoadingInterceptorService} from './shared/services/loading-interceptor.service';
import {TokenInterceptorService} from './shared/services/token-interceptor.service';
import {TokenRefreshInterceptorService} from './shared/services/token-refresh-interceptor.service';
import {NgIdleModule} from '@ng-idle/core';
import {NgIdleKeepaliveModule} from '@ng-idle/keepalive';
import {OnlineHelpService} from './shared/services/online-help.service';
import { registerLocaleData } from '@angular/common';
import es_CO from '@angular/common/locales/es-CO';
import { ValidateSlugService } from './shared/services/validate-slug.service';
import { ApiSigasService } from './shared/services/api-sigas.service';
import { LazyService } from './shared/services/lazy-service';
registerLocaleData(es_CO);

@NgModule({
  declarations: [AppComponent, AlertServiceComponent],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MalihuScrollbarModule.forRoot(),
    MarkdownModule.forRoot(),
    NgIdleModule,
    NgIdleKeepaliveModule.forRoot(),
    ServiceWorkerModule.register('/ngsw-worker.js', {
      enabled: environment.production
    }),
    RouterModule.forRoot(ROUTES, {
      useHash: true,
      preloadingStrategy: PreloadAllModules
    }),
    SharedModule,
    LayoutModule
  ],
  providers: [
    LoadingHttpService,
    AlertService,
    ApiService,
    AppErrorHandler,
    AuthService,
    ErrorService,
    JwtService,
    MessageService,
    OnlineHelpService,
    AuthGuard,
    {
      provide: APP_INITIALIZER,
      useFactory: MessagesLoader,
      deps: [MessageService],
      multi: true
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: LoadingInterceptorService,
      multi: true
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: TokenRefreshInterceptorService,
      multi: true
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: TokenInterceptorService,
      multi: true
    },
    {
      provide: ErrorHandler,
      useClass: AppErrorHandler
    },
    {
      provide: LOCALE_ID,
      useValue: 'es-co'
    },
    ValidateSlugService,
    LazyService,
    ApiSigasService
  ],
  bootstrap: [AppComponent],
  // entryComponents: [AlertServiceComponent],
  exports: []
})
export class AppModule {
}
