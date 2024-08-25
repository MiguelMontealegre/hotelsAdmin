import { HomePageComponent } from './pages/home-page/home-page.component';
import { InverseAuthGuard } from '../core/guards/inverse-auth.guard';
import { LayoutComponent } from './modules/layout/layout.component';
import { NgModule } from '@angular/core';
import { PendingWholesaleComponent } from '../account/auth/pending-wholesale/pending-wholesale.component';
import { PrivacyPolicyComponent } from './pages/privacy-policy/privacy-policy.component';
import { ReviewsComponent } from './pages/reviews/reviews.component';
import { RouterModule } from '@angular/router';
import { Routes } from '@angular/router';
import { TermsUseComponent } from './pages/terms-use/terms-use.component';
import { UserPivotGuard } from '../core/guards/user-pivot.guard';
const routes: Routes = [
  {
    path: '',
    component: HomePageComponent,
  },
  {
    path: 'meta',
    component: LayoutComponent,
    children: [
      {
        path: 'terms-use',
        component: TermsUseComponent,
      },
      {
        path: 'privacy-policy',
        component: PrivacyPolicyComponent,
      },
      {
        path: 'reviews',
        component: ReviewsComponent,
        canActivate: [UserPivotGuard]
      },
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class WebSiteRoutingModule { }
