import {ModuleWithProviders, NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {MzNavbarModule} from './navbar';
import {MzCardModule} from './card';
import {MzButtonModule} from './button';
import {MzIconModule} from './icon';
import {MzCollapsibleModule} from './collapsible';
import {MzCollectionModule} from './collection';
import {MzDropdownModule} from './dropdown';
import {MzModalModule} from './modal';
import {MzPaginationModule} from './pagination';
import {MzParallaxModule} from './parallax';
import {MzTabModule} from './tab';
import {MzSidenavModule} from './sidenav';
import {MzProgressModule} from './progress';
import {MzSpinnerModule} from './spinner';
import {MzBadgeModule} from './badge';
import {MzTooltipModule} from './tooltip';
import {MzToastModule} from './toast';
import {MzFeatureDiscoveryModule} from './feature-discovery';
import {MzMediaModule} from './media';
import {MzInputModule} from './input';
import {MzValidationModule} from './validation';
import {MzTextareaModule} from './textarea';
import {MzDatepickerModule} from './datepicker';
import {MzTimepickerModule} from './timepicker';
import {MzSelectModule} from './select';
import {MzCheckboxModule} from './checkbox';
import {FormsModule} from '@angular/forms';
import {MzChipModule} from './chip';
import {MzInjectionModule} from './shared/injection';
import {MzRadioButtonModule} from './radio-button';
import {MzSwitchModule} from './switch';
import {MzIconMdiModule} from '@ngx-materialize/icon-mdi';

const MZ_MODULES = [];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    MzBadgeModule,
    MzButtonModule,
    MzCardModule,
    MzCheckboxModule,
    MzChipModule,
    MzCollapsibleModule,
    MzCollectionModule,
    MzDatepickerModule,
    MzDropdownModule,
    MzFeatureDiscoveryModule,
    MzIconModule,
    MzIconMdiModule,
    MzInjectionModule,
    MzInputModule,
    MzMediaModule,
    MzModalModule,
    MzNavbarModule,
    MzPaginationModule,
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
  ],
  exports: [
    CommonModule,
    FormsModule,
    MzBadgeModule,
    MzButtonModule,
    MzCardModule,
    MzCheckboxModule,
    MzChipModule,
    MzCollapsibleModule,
    MzCollectionModule,
    MzDatepickerModule,
    MzDropdownModule,
    MzFeatureDiscoveryModule,
    MzIconModule,
    MzIconMdiModule,
    MzInjectionModule,
    MzInputModule,
    MzMediaModule,
    MzModalModule,
    MzNavbarModule,
    MzPaginationModule,
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
  ],
  declarations: [],
})
export class NgxMaterializeModule {
  static forRoot(): ModuleWithProviders {
    return {
      ngModule: NgxMaterializeModule,
    };
  }
}
