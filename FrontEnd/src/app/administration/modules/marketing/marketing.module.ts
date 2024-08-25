import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {
  NgbDropdownModule,
  NgbNavModule,
  NgbPaginationModule,
  NgbTooltipModule,
} from '@ng-bootstrap/ng-bootstrap';

import { NgSelectModule } from '@ng-select/ng-select';
import { EventService } from '@services/layout/event.service';
import { NgApexchartsModule } from 'ng-apexcharts';
import { NgxDropzoneModule } from 'ngx-dropzone';
import { UiSwitchModule } from 'ngx-ui-switch';
import { UiModule } from '@modules/ui/ui.module';
import { WidgetModule } from '@modules/widget/widget.module';

import { MarketingRoutingModule } from './marketing-routing.module';
import { LayoutModule } from './modules/layout/layout.module';

@NgModule({
  declarations: [
  ],
  imports: [
    CommonModule,  
    NgApexchartsModule,
    NgSelectModule,
    NgbPaginationModule,
    NgbDropdownModule,
    NgbNavModule,
    ReactiveFormsModule,
    FormsModule,
    NgxDropzoneModule,
    UiSwitchModule,
    NgbTooltipModule,
    LayoutModule,
   MarketingRoutingModule,
    UiModule,
    WidgetModule
  ],
  providers: [EventService],
})
export class MarketingModule { }
