import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CustomPopupRoutingModule } from './custom-popup-routing.module';
import { CustomPopupComponent } from './pages/custom-popup/custom-popup.component';
import { NgxDropzoneModule } from 'ngx-dropzone';
import { TranslateModule } from '@ngx-translate/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { EventService } from '@services/layout/event.service';
import { ModelService } from '@services/common/model.service';
import { Product } from '@models/products/product.model';
import { TestModalComponent } from './modals/test-modal/test-modal.component';
import { FormComponent } from './components/form/form.component';
import { NgbDropdownModule, NgbModal, NgbModule, NgbNavModule, NgbPaginationModule, NgbTooltipModule } from '@ng-bootstrap/ng-bootstrap';
import { ListComponent } from './pages/list/list.component';
import { LightboxModule } from 'ngx-lightbox';
import { Ng5SliderModule } from 'ng5-slider';
import { WidgetModule } from '@modules/widget/widget.module';
import { UiModule } from '@modules/ui/ui.module';
import { NgApexchartsModule } from 'ng-apexcharts';
import { NgSelectModule } from '@ng-select/ng-select';
import { UiSwitchModule } from 'ngx-ui-switch';

@NgModule({
  declarations: [
    CustomPopupComponent,
    TestModalComponent,
    FormComponent,
    ListComponent
  ],
  imports: [
    CommonModule,
    CustomPopupRoutingModule,
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
      provide: 'ProductService',
      useFactory: () => new ModelService<Product>(),
    },
  ],
  exports: [
    CustomPopupComponent,
    TestModalComponent,
    FormComponent,
    ListComponent
  ]
})
export class CustomPopupModule {
 }
