import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LayoutComponent } from './modules/layout/layout.component';

const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: 'custom-popup',
        loadChildren: () => import('./modules/custom-popup/custom-popup.module').then(m => m.CustomPopupModule),
      },
      {
        path: 'banners',
        loadChildren: () => import('./modules/banners/banners.module').then(m => m.BannersModule),

      }

      ]
  }
];



@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MarketingRoutingModule { }
