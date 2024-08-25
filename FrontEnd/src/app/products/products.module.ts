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
import { ListPageComponent as FavoritesListPageComponent } from './pages/favorites-list-page/list-page.component';
import { FilterProductsPipe } from '../core/pipes/filter.pipe';
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
import { Product } from '@models/products/product.model';
import { ProductDetailComponent } from './components/product-detail/product-detail.component';
import { ProductsRoutingModule } from './products-routing.module';
import { TranslateModule } from '@ngx-translate/core';
import { UiModule } from '@modules/ui/ui.module';
import { UiSwitchModule } from 'ngx-ui-switch';
import { WidgetModule } from '@modules/widget/widget.module';

@NgModule({
  declarations: [
    ListPageComponent, DetailPageComponent, ProductDetailComponent, FavoritesListPageComponent, FilterProductsPipe,
  ],
  imports: [
    CommonModule,
    // PunctuationPipe,

    ProductsRoutingModule,
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
      provide: 'ProductService',
      useFactory: () => new ModelService<Product>(),
    },
  ],
})
export class ProductsModule {}
