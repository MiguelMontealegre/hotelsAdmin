import { BehaviorSubject, takeUntil } from 'rxjs';
import { Component, Inject } from '@angular/core';
import Swal, { SweetAlertResult } from 'sweetalert2';
import { getMenuByRole, getRouteByRole } from '@functions/routing';

import { ActivatedRoute } from '@angular/router';
import { ApiResponse } from '@models/common/api-response.model';
import { AuthenticationApiService } from '@services/account/authentication-api.service';
import { AuthenticationService } from '@services/account/authentication.service';
import { CartProduct } from '@models/cart/cart-product.model';
import { CollectionComponent } from '@components/abstract/collection.component';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { FormControl } from '@angular/forms';
import { Location } from '@angular/common';
import { MenuItem } from '@models/layout/menu.model';
import { ModelService } from '@services/common/model.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Options } from 'ng5-slider';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Product } from '@models/products/product.model';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { User } from '@models/account/user.model';
import { alertFire } from '@functions/alerts';
import { map } from 'lodash';
import { CountCartService } from '@services/layout/count-cart.service';

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
    private countCartService: CountCartService,
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


  addToCart(product: Product) {

    if (product?.sizes?.length > 0 || product?.colors?.length > 0) {
      alertFire('Este producto requiere que se seleccionen detalles adicionales, seras redirigido a el detalle para continuar con el proceso').then(result => {
        if (result.value) {
          this.router.navigate(['/products/detail/', product.id]);
          return;
        }
      });
    } else {
      const quantity = 1;
      if (this.authenticationService.authService.model) {
        const productData = {
          productId: product.id,
          userId: this.authenticationService.authService.model.id,
          quantity: quantity,
        };
        this.api2.post(`cart-products/add-product`, productData)
          .subscribe(r => {
            if (r) {
              this.countCartService.updateCartCount();
              this.toastr.success('Producto añadido exitosamente.')
            }
          },
            error => {
              this.toastr.error('La cantidad es Invalida!' || error || error?.error || error?.error?.message);
            });
      } else {
        const productData = {
          product: product,
          userId: null,
          quantity: quantity,
        };

        const cart = this.getCartFromLocalStorage();
        const cartProducts = cart.filter(p => p.product.id === product.id);
        const cartProductTotalQuantity = cartProducts.reduce((total, product) => total + product.quantity, 0);
        let productMatch = false;

        if (product.availableQuantity < quantity + cartProductTotalQuantity) {
          this.toastr.error('La cantidad es invalida!');
          return;
        }
        if (cart.length > 0) {
          for (const cartProduct of cartProducts) {
            if (cartProduct) {
              cartProduct.quantity = cartProduct.quantity + quantity;
              localStorage.setItem('cart', JSON.stringify(cart));
              productMatch = true;
              this.countCartService.updateCartCount();
              this.toastr.success('Producto añadido exitosamente.')
            }
          }
          if (!productMatch) {
            cart.unshift(productData);
            localStorage.setItem('cart', JSON.stringify(cart));
            this.countCartService.updateCartCount();
            this.toastr.success('Producto añadido exitosamente.')
          }
        } else {
          cart.unshift(productData);
          localStorage.setItem('cart', JSON.stringify(cart));
          this.countCartService.updateCartCount();
          this.toastr.success('Producto añadido exitosamente.')
        }
      }
    }
  }

  getCartFromLocalStorage(): CartProduct[] {
    const cartItemsJSON = localStorage.getItem('cart');
    if (cartItemsJSON) {
      return JSON.parse(cartItemsJSON);
    } else {
      return [];
    }
  }

}
