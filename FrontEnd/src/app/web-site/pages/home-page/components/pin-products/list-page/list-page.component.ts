import { BehaviorSubject } from 'rxjs';
import { Component } from '@angular/core';

import { ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '@services/account/authentication.service';
import { CollectionComponent } from '@components/abstract/collection.component';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { Location } from '@angular/common';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Product } from '@models/products/product.model';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { map } from 'lodash';

@Component({
  selector: 'app-pin-products-list-page',
  templateUrl: './list-page.component.html',
  styleUrls: ['./list-page.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'products' },
    CommonApiService,
  ],
})
export class PinProductsListPageComponent extends CollectionComponent<Product> {
  breadCrumbs = [
    { label: 'Favoritos', active: true },
  ];
  TRANSLATE_KEY= 'ADMIN.USERS.PAGES.LIST.'


  carouselOption: OwlOptions = {
    loop: true,
    margin: 40,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    nav: false,
    dots: true,
    responsive: {
      0: {
        items: 1,
      },
      480: {
        items: 2,
      },
      768: {
        items: 3,
      },
    },
  }
  roleNames: string[] = [];

  private subject$: BehaviorSubject<Product | null> =
    new BehaviorSubject<Product | null>(null);

  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    private api2: CommonVerbsApiService,
    service: CollectionService<Product>,
    private toastr: ToastrService,
    private route: ActivatedRoute,
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
      { column: 'createdAt', direction: 'DESC' },
      [],
      [
        {
          key: 'pin',
          values: [true]
        },
      ]
    );
  }

  ngOnInit(): void {
    super.ngOnInit();
    this.checkRole();
  }

  checkRole() {
    if (this.authenticationService?.authService?.model) {
      const user = this.authenticationService?.authService?.model;
      const roleNames = map(user.roles, r => r.name);
      this.roleNames = roleNames;
    }
  }


  like(product: Product) {
    if (this.authenticationService.authService.model) {
      this.api.post<{ status: string }>(`/like/${product.id}`)
        .subscribe(r => {
          if (r.status) {
            if (r.status === 'attached') {
              product.userLike = true;
              product.likesCount += 1;
            } else {
              product.userLike = false;
              product.likesCount -= 1;
            }
          }
        });
    } else {
      this.router
        .navigate([`/account/auth/login-2`])
        .then();
    }
  }
}
