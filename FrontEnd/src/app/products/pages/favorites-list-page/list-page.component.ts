import { Component, Inject } from '@angular/core';
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
import { ToastrService } from 'ngx-toastr';
import { FormControl } from '@angular/forms';
import { Product } from '@models/products/product.model';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { alertFire } from '@functions/alerts';

@Component({
  selector: 'app-list-page',
  templateUrl: './list-page.component.html',
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'products' },
    CommonApiService,
    CommonVerbsApiService
  ],
})
export class ListPageComponent extends CollectionComponent<Product> {
  breadCrumbs = [
    { label: 'Productos', active: true },
  ];
  statusControl = new FormControl('0');
  TRANSLATE_KEY= 'ADMIN.USERS.PAGES.LIST.'

  private subject$: BehaviorSubject<Product | null> =
    new BehaviorSubject<Product | null>(null);

  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<Product>,
    private toastr: ToastrService,
    private route: ActivatedRoute,
    private apiAuth: AuthenticationApiService,
    @Inject('MenuService')
    public menuService: ModelService<MenuItem[]>,
    private modal: NgbModal,
    public authenticationService: AuthenticationService,
    private api2: CommonVerbsApiService,
  ) {
    super(
      router,
      location,
      ``,
      api,
      service,
      10,
      { column: 'createdAt', direction: 'DESC' },
      [],
      [
        {
          key: 'userLikes',
          values: [authenticationService.authService.model.id]
        },
      ]
    );
  }

  ngOnInit(): void {
    super.ngOnInit();
    const subscribe1 = this.service.clear$
      .pipe(takeUntil(this.destroy$))
      .subscribe(options => {
        this.statusControl.patchValue('0', {
          emitEvent: false,
        });
      });
    this.unsubscribe.push(subscribe1);
  }


  toggleLike(product: Product){
    alertFire('Quieres quitarlo de tus favoritos').then(result => {
      if (result.value) {
        this.api.post<{status: string}>(`/like/${product.id}`)
        .subscribe(r => {
          this.toastr.success('Hecho');
          this.clear();
        });
      }
    });
  }

}
