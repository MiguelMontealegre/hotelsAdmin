import { NgbAccordionModule, NgbModule, NgbPagination, NgbTimepickerModule } from '@ng-bootstrap/ng-bootstrap';

import { CommonModule } from '@angular/common';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { CoreModule } from 'src/app/core/core.module';
import { DetailPageComponent } from './pages/detail-page/detail-page.component';
import { FlatpickrModule } from 'angularx-flatpickr';
import { FormsModule } from '@angular/forms';
import { ListPageComponent } from './pages/list-page/list-page.component';
import { ModelService } from '@services/common/model.service';
import { NgModule } from '@angular/core';
import { NgSelectModule } from '@ng-select/ng-select';
import { NgbDatepickerModule } from '@ng-bootstrap/ng-bootstrap';
import { NgbDropdownModule } from '@ng-bootstrap/ng-bootstrap';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';
import { NgbRatingModule } from '@ng-bootstrap/ng-bootstrap';
import { Order } from '@models/orders/order.model';
import { OrderApiResolver } from '@resolvers/order-api.resolver';
import { OrderDetailComponent } from './components/order-detail/order-detail.component';
import { OrdersRoutingModule } from './orders-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { UiModule } from '@modules/ui/ui.module';
import { NgbModal,NgbNavModule,  NgbTooltipModule } from '@ng-bootstrap/ng-bootstrap';
import { OrderPassengersComponent } from './components/order-passengers-list/order-passengers-list.component';
@NgModule({
  declarations: [
    ListPageComponent,  DetailPageComponent, OrderDetailComponent, OrderPassengersComponent
  ],
  imports: [
    OrdersRoutingModule,
    CoreModule,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NgSelectModule,
    NgbDropdownModule,
    NgbNavModule,
    NgbTooltipModule,
    NgbPagination,
    NgbPaginationModule,
    NgbDatepickerModule,
    UiModule,
    NgbRatingModule,
    NgbAccordionModule,
    NgbModule,
    TranslateModule,
    NgbTimepickerModule,
    FlatpickrModule.forRoot(),
  ],
  providers: [
    CommonVerbsApiService,
    OrderApiResolver,
    {
      provide: 'OrderService',
      useFactory: () => new ModelService<Order>(),
    },
  ],
})
export class OrdersModule { }
