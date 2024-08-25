import { LayoutComponent } from './modules/layout/layout.component';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { Routes } from '@angular/router';
import { AuthGuard } from '../core/guards/auth.guard';
import { RoleGuard } from '../core/guards/role.guard';
import { StylesComponent } from './modules/users/pages/styles/styles.component';

const routes: Routes = [
  {
    path: '',
    component: LayoutComponent,
    children: [
      {
        path: 'users',
        loadChildren: () =>
          import('./modules/users/users.module').then(
            m => m.UsersModule
          ),
          canActivate: [RoleGuard],
          data: { roles: ['ADMIN'] }
      },
      {
        path: 'hotels',
        loadChildren: () =>
          import('./modules/hotels/hotels.module').then(
            m => m.HotelsModule
          ),
          canActivate: [ RoleGuard],
          data: { roles: ['ADMIN'] }
      },
      {
        path: 'products',
        loadChildren: () =>
          import('./modules/products/products.module').then(
            m => m.ProductsModule
          ),
          canActivate: [ RoleGuard],
          data: { roles: ['ADMIN'] }
      },
      {
        path: 'categories',
        loadChildren: () =>
          import('./modules/categories/categories.module').then(
            m => m.CategoriesModule
          ),
          canActivate: [ RoleGuard],
          data: { roles: ['ADMIN'] }
      },
      {
        path: 'tags',
        loadChildren: () =>
          import('./modules/tags/tags.module').then(
            m => m.TagsModule,
          ),
          canActivate: [RoleGuard],
          data: { roles: ['ADMIN'] }
      },
      {
        path: 'orders',
        loadChildren: () =>
          import('./modules/orders/orders.module').then(
            m => m.OrdersModule
          ),
        canActivate: [AuthGuard]
      },
      {
        path: 'marketing',
        loadChildren: () =>
          import('./modules/marketing/marketing.module').then(
            m => m.MarketingModule
          ),
          canActivate: [ RoleGuard],
          data: { roles: ['ADMIN','MARKETER_USER'] }
      },
      {
        path: 'sales',
        loadChildren: () =>
         import('./modules/sales/sales.module').then(
          m => m.SalesModule
        ),
        canActivate: [ RoleGuard],
        data: { roles: ['ADMIN','SALE_USER'] }
      },
      {
        path: 'styles',
        component: StylesComponent,
      } ,

    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AdministrationRoutingModule {}
