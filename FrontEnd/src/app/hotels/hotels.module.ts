import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {
  NgbDropdownModule,
  NgbNavModule,
  NgbPaginationModule,
  NgbTooltipModule,
} from '@ng-bootstrap/ng-bootstrap';

import { CKEditorModule } from '@ckeditor/ckeditor5-angular';
import { CommonModule } from '@angular/common';
import { DetailPageComponent } from './pages/detail-page/detail-page.component';
import { EventService } from '@services/layout/event.service';
import { GoogleMapsModule } from '@angular/google-maps';
import { LayoutModule } from './modules/layout/layout.module';
import { LightboxModule } from 'ngx-lightbox';
import { ListPageComponent } from './pages/list-page/list-page.component';
import { ModelService } from '@services/common/model.service';
import { Ng5SliderModule } from 'ng5-slider';
import { NgApexchartsModule } from 'ng-apexcharts';
import { NgModule } from '@angular/core';
import { NgSelectModule } from '@ng-select/ng-select';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgxDropzoneModule } from 'ngx-dropzone';
import { TranslateModule } from '@ngx-translate/core';
import { UiModule } from '@modules/ui/ui.module';
import { UiSwitchModule } from 'ngx-ui-switch';
import { WidgetModule } from '@modules/widget/widget.module';
import { HotelsRoutingModule } from './hotels-routing.module';
import { HotelDetailComponent } from './components/product-detail/hotel-detail.component';
import { Hotel } from '@models/hotels/hotel.model';

@NgModule({
  declarations: [
    ListPageComponent, DetailPageComponent, HotelDetailComponent,
  ],
  imports: [
    CommonModule,
    HotelsRoutingModule,
    NgApexchartsModule,
    NgSelectModule,
    NgbPaginationModule,
    NgbDropdownModule,
    NgbNavModule,
    ReactiveFormsModule,
    FormsModule,
    NgxDropzoneModule,
    UiSwitchModule,
    NgbModule,
    UiModule,
    WidgetModule,
    LayoutModule,
    NgbTooltipModule,
    TranslateModule,
    Ng5SliderModule,
    LightboxModule,
    CKEditorModule,
    GoogleMapsModule
  ],
  providers: [
    EventService,
    {
      provide: 'HotelService',
      useFactory: () => new ModelService<Hotel>(),
    },
  ],
})
export class HotelsModule {}
