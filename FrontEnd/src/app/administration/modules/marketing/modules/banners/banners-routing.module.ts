import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ListPageComponent } from './pages/list-page/list-page.component';
import { FormPageComponent } from './pages/form-page/form-page.component';

const routes: Routes = [
  {
    path: '',
  component: ListPageComponent,
  },
  {
    path: 'forms',
    children: [
      {
        path: '',
        component: FormPageComponent,
      },
      {
        path: ':id',
        component: FormPageComponent,
      },
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class BannersRoutingModule { }
