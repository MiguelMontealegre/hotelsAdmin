import { DetailPageComponent } from './pages/detail-page/detail-page.component';
import { LayoutComponent } from './modules/layout/layout.component';
import { ListPageComponent } from './pages/list-page/list-page.component';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { Routes } from '@angular/router';
import { UserPivotGuard } from '../core/guards/user-pivot.guard';

const routes: Routes = [
  {
    path: '',
    component: LayoutComponent,
    children: [
      {
        path: 'portal',
        component: ListPageComponent,
        canActivate: [UserPivotGuard]
      },
      {
        path: 'detail',
        children: [
          {
            path: ':id',
            component: DetailPageComponent,
          },
        ],
      },
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class HotelsRoutingModule {}
