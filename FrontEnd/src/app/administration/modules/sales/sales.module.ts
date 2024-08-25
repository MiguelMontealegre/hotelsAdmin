import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SalesRoutingModule } from './sales-routing.module';
import { DashboardComponent } from './pages/dashboard/dashboard.component';


import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {
  NgbDropdownModule,
  NgbNavModule,
  NgbPaginationModule,
  NgbTooltipModule,
} from '@ng-bootstrap/ng-bootstrap';

import { EventService } from '@services/layout/event.service';
import { LightboxModule } from 'ngx-lightbox';
import { ModelService } from '@services/common/model.service';
import { Ng5SliderModule } from 'ng5-slider';
import { NgApexchartsModule } from 'ng-apexcharts';
import { NgSelectModule } from '@ng-select/ng-select';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgxDropzoneModule } from 'ngx-dropzone';
import { TranslateModule } from '@ngx-translate/core';
import { UiModule } from '@modules/ui/ui.module';
import { ChartsModule } from 'ng2-charts';
import { UiSwitchModule } from 'ngx-ui-switch';
import { WidgetModule } from '@modules/widget/widget.module';
import { HTTP_INTERCEPTORS, HttpClientJsonpModule, HttpClientModule } from '@angular/common/http';
import { GenericServicesModule } from '@modules/generic-services/generic-services.module';
import { ApiInterceptor } from '@interceptors/api.interceptor';
import { CustomDateAdapter } from '@utils/custom-date-adapter';
import { MainDataComponent } from './components/main-data/main-data.component';
import { filter } from 'rxjs';
import { FiltersComponent } from './components/filters/filters.component';
import { SalesUserComponent } from './components/sales-user/sales-user.component';
import { SaleProductsComponent } from './components/sale-products/sale-products.component';

@NgModule({
  declarations: [
    DashboardComponent,
    MainDataComponent,
    FiltersComponent,
    SalesUserComponent,
    SaleProductsComponent
  ],
  imports: [
    CommonModule,
    SalesRoutingModule,
    CommonModule,
    ReactiveFormsModule,
    CommonModule,
    FormsModule,
    ChartsModule,
    NgApexchartsModule,
    ReactiveFormsModule,
    NgxDropzoneModule,
    NgSelectModule,
    NgbPaginationModule,
    NgbDropdownModule,
    NgbNavModule,
    HttpClientJsonpModule,
    HttpClientModule,
    UiModule,
    NgbModule,
    GenericServicesModule,
    TranslateModule,

  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true },
    CustomDateAdapter
  ],
})

export class SalesModule { }
