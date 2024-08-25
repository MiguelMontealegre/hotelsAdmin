import { Component, Inject } from '@angular/core';
import { getMenuByRole, getRouteByRole } from '@functions/routing';
import { ActivatedRoute } from '@angular/router';
import { AuthenticationApiService } from '@services/account/authentication-api.service';
import { AuthenticationService } from '@services/account/authentication.service';
import { BehaviorSubject, takeUntil } from 'rxjs';
import { CollectionComponent } from '@components/abstract/collection.component';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { Location } from '@angular/common';
import { MenuItem } from '@models/layout/menu.model';
import { ModelService } from '@services/common/model.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';
import { SweetAlertResult } from 'sweetalert2';
import { ToastrService } from 'ngx-toastr';
import { User } from '@models/account/user.model';
import { FormControl } from '@angular/forms';
import { Member } from '@models/account/member.model';
import { SaleProduct } from '@models/sales/sale-product-model';

@Component({
  selector: 'app-sale-products',
  templateUrl: './sale-products.component.html',
  styleUrls: ['./sale-products.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'analytics/sales-products' },
    CommonApiService,
  ],
})
export class SaleProductsComponent  extends CollectionComponent<SaleProduct> {
  graphicParams = [
    {
      chart: 'VENTAS',
      type: 'line',
      title: 'Ventas',
    },
  ];
  isGraphVisible: boolean[] = [false];
  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<SaleProduct>,
    private toastr: ToastrService,
    private route: ActivatedRoute,
    private apiAuth: AuthenticationApiService,
    @Inject('AuthService')
    public authService: ModelService<SaleProduct>,
    @Inject('MenuService')
    public menuService: ModelService<MenuItem[]>,
    private modal: NgbModal,
    public authenticationService: AuthenticationService
  ) {
    super(
      router,
      location,
      ``,
      api,
      service,
      10,
      { column: 'getTotalPurchaseValueAttribute', direction: 'DESC' },
    );
    
  }
  
TRANSLATE_KEY= 'ADMIN.USERS.PAGES.LIST.'
ngOnInit(): void {
  super.ngOnInit();
}


  toggleGraphVisibility(index: number): void {
    this.isGraphVisible[index] = !this.isGraphVisible[index];
  }

}
