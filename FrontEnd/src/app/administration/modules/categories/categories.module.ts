import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {
  NgbDropdownModule,
  NgbNavModule,
  NgbPaginationModule,
  NgbTooltipModule,
} from '@ng-bootstrap/ng-bootstrap';

import { CategoriesRoutingModule } from './categories-routing.module';
import { Category } from '@models/categories/category.model';
import { CommonModule } from '@angular/common';
import { EditFormComponent } from './components/edit-form/edit-form.component';
import { EventService } from '@services/layout/event.service';
import { FormPageComponent } from './pages/form-page/form-page.component';
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
import { TranslateModule } from '@ngx-translate/core';
import { UiModule } from '@modules/ui/ui.module';
import { UiSwitchModule } from 'ngx-ui-switch';
import { WidgetModule } from '@modules/widget/widget.module';

@NgModule({
  declarations: [
    ListPageComponent, FormPageComponent, EditFormComponent
  ],
  imports: [
    CommonModule,
    CategoriesRoutingModule,
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
    NgbTooltipModule,
    TranslateModule,
    Ng5SliderModule,
    LightboxModule
  ],
  providers: [
    EventService,
    {
      provide: 'CategoryService',
      useFactory: () => new ModelService<Category>(),
    },
  ],
})
export class CategoriesModule {}
