import { Lightbox, LightboxModule } from 'ngx-lightbox';
import {
  NgbAccordionModule,
  NgbAlertModule,
  NgbCarouselModule,
  NgbDropdownModule,
  NgbModule,
  NgbNavModule,
  NgbTooltipModule,
} from '@ng-bootstrap/ng-bootstrap';

import { AuthenticationModule } from '@modules/authentication/authentication.module';
import { CarouselModule } from 'ngx-owl-carousel-o';
import { CategoriesListPageComponent } from './pages/home-page/components/categories/list-page/list-page.component';
import { CommonModule } from '@angular/common';
import { CommonVerbsApiService } from 'src/app/core/services/common/common-verbs-api.service';
import { EventService } from '@services/layout/event.service';
import { FormsModule } from '@angular/forms';
import { GoogleMapsModule } from '@angular/google-maps';
import { HeaderModule } from '@modules/header/header.module';
import { HomePageComponent } from './pages/home-page/home-page.component';
import { LayoutModule } from './modules/layout/layout.module';
import { ListPageComponent } from './pages/home-page/components/banners/list-page/list-page.component';
import { NgModule } from '@angular/core';
import { NgxDropzoneModule } from 'ngx-dropzone';
import { PendingWholesaleComponent } from '../account/auth/pending-wholesale/pending-wholesale.component';
import { PinProductsListPageComponent } from './pages/home-page/components/pin-products/list-page/list-page.component';
import { PrivacyPolicyComponent } from './pages/privacy-policy/privacy-policy.component';
import { ProductsModule } from '../products/products.module';
import { PromotionsModalComponent } from './pages/home-page/modals/promotions-modal/promotions-modal.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ReviewFormModalComponent } from './pages/reviews/components/modal-form/review-form-modal.component';
import { ReviewsComponent } from './pages/reviews/reviews.component';
import { ReviewsListPageComponent } from './pages/home-page/components/reviews/list-page/list-page.component';
import { RouterModule } from '@angular/router';
import { ScrollToModule } from '@nicky-lenaers/ngx-scroll-to';
import { ScrollspyDirective } from './pages/home-page/scrollspy.directive';
import { SimplebarAngularModule } from '@modules/simplebar-angular/simplebar-angular.module';
import { TermsUseComponent } from './pages/terms-use/terms-use.component';
import { TranslateModule } from '@ngx-translate/core';
import { UiModule } from '@modules/ui/ui.module';
import { WebSiteRoutingModule } from './web-site-routing.module';
import { ContactUsComponent } from './pages/contact-us/contact-us.component';

@NgModule({
  declarations: [
    HomePageComponent,
    ScrollspyDirective,
    TermsUseComponent,
    PrivacyPolicyComponent,
    CategoriesListPageComponent,
    PromotionsModalComponent,
    ReviewsComponent,
    ReviewFormModalComponent,
    ListPageComponent,
    PendingWholesaleComponent,
    ReviewsListPageComponent,
    PinProductsListPageComponent,
    ContactUsComponent
  ],
  imports: [
    TranslateModule,
    CommonModule,
    WebSiteRoutingModule,
    NgbCarouselModule,
    FormsModule,
    ReactiveFormsModule,
    NgbAlertModule,
    NgbTooltipModule,
    NgbNavModule,
    NgbTooltipModule,
    LightboxModule,
    ScrollToModule.forRoot(),
    NgbModule,
    CarouselModule,
    AuthenticationModule,
    NgxDropzoneModule,
    RouterModule,
    TranslateModule,
    NgbDropdownModule,
    SimplebarAngularModule,
    HeaderModule,
    NgbAccordionModule,
    UiModule,
    CarouselModule,
    LayoutModule,
    GoogleMapsModule
  ],
  providers: [EventService,  CommonVerbsApiService,Lightbox]
})
export class WebSiteModule {}
