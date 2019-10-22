import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HttpClientModule} from '@angular/common/http';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {RouterModule} from '@angular/router';
import {PipesModule} from './pipes/pipes.module';
import {
  MzBadgeModule,
  MzButtonModule,
  MzCardModule,
  MzCheckboxModule,
  MzCollapsibleModule,
  MzCollectionModule,
  MzDatepickerModule,
  MzDropdownModule,
  MzIconModule,
  MzIconMdiModule,
  MzInputModule,
  MzMediaModule,
  MzModalModule,
  MzNavbarModule,
  MzParallaxModule,
  MzProgressModule,
  MzRadioButtonModule,
  MzSelectModule,
  MzSidenavModule,
  MzSpinnerModule,
  MzSwitchModule,
  MzTabModule,
  MzTextareaModule,
  MzTimepickerModule,
  MzToastModule,
  MzTooltipModule,
  MzValidationModule,
  MzInjectionModule,
  MzPaginationModule,
  MzFeatureDiscoveryModule, JsonValidationDirective
} from '@ngx-materialize';
import {MomentModule} from 'angular2-moment';
import {HasPermissionDirective} from './directives/has-permission.directive';
import {HasRoleDirective} from './directives/has-role.directive';
import {IsLoggedDirective} from './directives/is-logged.directive';
import {LoadingDirective} from './directives/loading.directive';
import {ScrollToDirective} from './directives/scroll-to.directive';
import {DataTablesModule} from 'angular-datatables';
import {EditorModule} from '@tinymce/tinymce-angular';
import {PaginationComponent} from './layout/pagination/pagination.component';
import {OnlineHelpsButtonComponent} from '../modules/online-helps/online-helps-button/online-helps-button.component';
import {OnlineHelpButtonComponent} from '../modules/online-helps/online-help-button/online-help-button.component';
import {Select2Module} from 'ng2-select2';
import {FormSelectComponent} from './form/select/select.component';
import {ValidationComponent} from './form/validations/validations.component';
import {FormTextComponent} from './form/input/input.component';
import {AutocompleteComponent} from './form/autocomplete/autocomplete.component';
import {UserCanDirective} from './directives/user-can.directive';
import {UserIsDirective} from './directives/user-is.directive';
import {UserIsLoggedDirective} from './directives/user-is-logged.directive';
import {ElfinderComponent} from './components/elfinder/elfinder.component';
import {LocationsQuotationsComponent} from './components/locations-quotations/locations-quotations.component';
import {ImageUploadComponent} from './components/image-upload/image-upload.component';
import {ChartsModule} from 'ng2-charts';
import {FullCalendarModule} from 'ng-fullcalendar';
import {SelectSearchComponent} from './form/select-search/select-search.component';
import {NgxSelectModule} from 'ngx-select-ex';
import {
  RecaptchaModule,
  RecaptchaLoaderService,
  RECAPTCHA_SETTINGS,
  RECAPTCHA_LANGUAGE,
  RecaptchaSettings
} from 'ng-recaptcha';
import {RecaptchaFormsModule} from 'ng-recaptcha/forms';

import {SelectAllComponent} from './components/select-all/select-all.component';
import {MzCarouselDirective} from '@ngx-materialize/directives/mz-carousel.directive';
import {MzMaterialboxDirective} from '@ngx-materialize/directives/mz-materialbox.directive';
import {MaskDirective} from './directives/mask.directive';
import {NgxSummernoteModule} from 'ngx-summernote';

@NgModule({
  imports: [
    CommonModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    RouterModule,
    PipesModule,
    MzButtonModule,
    MzCheckboxModule,
    MzDatepickerModule,
    MzValidationModule,
    MzInputModule,
    MzRadioButtonModule,
    MzSelectModule,
    MzSwitchModule,
    MzTextareaModule,
    MzTimepickerModule,
    MzCardModule,
    MzCollapsibleModule,
    MzCollectionModule,
    MzDropdownModule,
    MzModalModule,
    MzNavbarModule,
    MzParallaxModule,
    MzSidenavModule,
    MzTabModule,
    MzProgressModule,
    MzSpinnerModule,
    MzBadgeModule,
    MzIconModule,
    MzIconMdiModule,
    MzInjectionModule,
    MzToastModule,
    MzTooltipModule,
    MzPaginationModule,
    MzMediaModule,
    MzFeatureDiscoveryModule,
    MomentModule,
    Select2Module,
    DataTablesModule,
    EditorModule,
    ChartsModule,
    FullCalendarModule,
    NgxSelectModule,
    RecaptchaModule,
    RecaptchaFormsModule,
    NgxSummernoteModule,
  ],
  exports: [
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    RouterModule,
    PipesModule,
    MzButtonModule,
    MzCheckboxModule,
    MzDatepickerModule,
    MzValidationModule,
    MzInputModule,
    MzRadioButtonModule,
    MzSelectModule,
    MzSwitchModule,
    MzTextareaModule,
    MzTimepickerModule,
    MzCardModule,
    MzCollapsibleModule,
    MzCollectionModule,
    MzDropdownModule,
    MzModalModule,
    MzNavbarModule,
    MzParallaxModule,
    MzSidenavModule,
    MzTabModule,
    MzProgressModule,
    MzSpinnerModule,
    MzBadgeModule,
    MzIconModule,
    MzIconMdiModule,
    MzInjectionModule,
    MzToastModule,
    MzTooltipModule,
    MzMediaModule,
    MzPaginationModule,
    MzFeatureDiscoveryModule,
    Select2Module,
    MomentModule,
    DataTablesModule,
    EditorModule,
    HasPermissionDirective,
    HasRoleDirective,
    IsLoggedDirective,
    LoadingDirective,
    ScrollToDirective,
    PaginationComponent,
    OnlineHelpsButtonComponent,
    OnlineHelpButtonComponent,
    FormSelectComponent,
    ValidationComponent,
    FormTextComponent,
    AutocompleteComponent,
    UserCanDirective,
    UserIsDirective,
    UserIsLoggedDirective,
    ElfinderComponent,
    LocationsQuotationsComponent,
    SelectAllComponent,
    ImageUploadComponent,
    ChartsModule,
    FullCalendarModule,
    NgxSelectModule,
    SelectSearchComponent,
    RecaptchaModule,
    RecaptchaFormsModule,
    MzCarouselDirective,
    MzMaterialboxDirective,
    JsonValidationDirective,
    MaskDirective,
    NgxSummernoteModule,
  ],
  declarations: [
    HasPermissionDirective,
    HasRoleDirective,
    IsLoggedDirective,
    LoadingDirective,
    ScrollToDirective,
    PaginationComponent,
    OnlineHelpsButtonComponent,
    OnlineHelpButtonComponent,
    FormSelectComponent,
    ValidationComponent,
    FormTextComponent,
    AutocompleteComponent,
    UserCanDirective,
    UserIsDirective,
    UserIsLoggedDirective,
    ElfinderComponent,
    LocationsQuotationsComponent,
    ImageUploadComponent,
    SelectSearchComponent,
    SelectAllComponent,
    MzCarouselDirective,
    MzMaterialboxDirective,
    JsonValidationDirective,
    MaskDirective
  ],
  entryComponents: [
    PaginationComponent,
    OnlineHelpsButtonComponent,
    OnlineHelpButtonComponent,
    FormSelectComponent,
    ValidationComponent,
    FormTextComponent,
    AutocompleteComponent,
    ElfinderComponent,
    LocationsQuotationsComponent,
    SelectAllComponent,
    ImageUploadComponent,
    SelectSearchComponent,
  ],
  providers: [
    {
      provide: RECAPTCHA_SETTINGS,
      useValue: {
        siteKey: '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
      } as RecaptchaSettings
    },
    {
      provide: RECAPTCHA_LANGUAGE,
      useValue: 'es'
    }
  ]
})
export class SharedModule {
}
