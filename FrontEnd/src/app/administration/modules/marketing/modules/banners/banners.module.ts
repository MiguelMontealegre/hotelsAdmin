import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { BannersRoutingModule } from './banners-routing.module';
import { ListPageComponent } from './pages/list-page/list-page.component';
import { FormPageComponent } from './pages/form-page/form-page.component';
import { EditFormComponent } from './components/edit-form/edit-form.component';


import { NgxDropzoneModule } from 'ngx-dropzone';
import { TranslateModule } from '@ngx-translate/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { EventService } from '@services/layout/event.service';
import { ModelService } from '@services/common/model.service';
import { Product } from '@models/products/product.model';
import { NgbDropdownModule, NgbModal, NgbModule, NgbNavModule, NgbPaginationModule, NgbTooltipModule } from '@ng-bootstrap/ng-bootstrap';

import { LightboxModule } from 'ngx-lightbox';
import { Ng5SliderModule } from 'ng5-slider';
import { WidgetModule } from '@modules/widget/widget.module';
import { UiModule } from '@modules/ui/ui.module';
import { NgApexchartsModule } from 'ng-apexcharts';
import { NgSelectModule } from '@ng-select/ng-select';
import { UiSwitchModule } from 'ngx-ui-switch';
import { CustomPopupRoutingModule } from '../custom-popup/custom-popup-routing.module';
import { Banner } from '@models/banners/banner.model';
@NgModule({
  declarations: [
    ListPageComponent,
    FormPageComponent,
    EditFormComponent
  ],
  imports: [
    CommonModule,
    BannersRoutingModule,
    NgxDropzoneModule,
    ReactiveFormsModule,
    NgbPaginationModule,
    TranslateModule,
    FormsModule,
    NgbModule,
    UiModule,
    WidgetModule,
    NgbTooltipModule,
    Ng5SliderModule,
    LightboxModule,
    NgApexchartsModule,
    NgSelectModule,
    NgbDropdownModule,
    NgbNavModule,
    UiSwitchModule,
  ],
  providers: [
    EventService,
    {
      provide: 'bannerService',
      useFactory: () => new ModelService<Banner>(),
    },
  ],
  exports: [
    ListPageComponent,
    FormPageComponent,
    EditFormComponent
  ]
})
export class BannersModule { }
