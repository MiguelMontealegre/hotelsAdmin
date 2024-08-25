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
import { Options } from 'ng5-slider';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { Category } from '@models/categories/category.model';
import { map } from 'lodash';
import { Tag } from '@models/tags/tag.model';
import { alertFire } from '@functions/alerts';
import { CountCartService } from '@services/layout/count-cart.service';
import { Hotel } from '@models/hotels/hotel.model';

@Component({
  selector: 'app-list-page',
  templateUrl: './list-page.component.html',
  styleUrls: ['./list-page.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'hotels' },
    CommonApiService,
    CommonVerbsApiService
  ],
})
export class ListPageComponent extends CollectionComponent<Hotel> {
  breadCrumbs = [
    { label: 'Hotels', active: true },
  ];
  statusControl = new FormControl('0');
  TRANSLATE_KEY = 'ADMIN.USERS.PAGES.LIST.'
  pricevalue = 0;
  maxVal = 240000;
  lastPricevalue: number;
  lastMaxVal: number;
  hotels = [];
  priceoption: Options = {
    floor: 0,
    ceil: 900000,
    translate: (value: number): string => {
      const integerPart = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      return '$ ' + integerPart;
    },
  };
  currentCategory: Category;
  categoriesControl = new FormControl(null);
  categories: Category[] = [];

  currentTag: Tag;
  tagsControl = new FormControl(null);
  tags: Tag[] = [];

  discountControl = new FormControl(null);

  roleNames: string[] = [];

  private subject$: BehaviorSubject<Hotel | null> =
    new BehaviorSubject<Hotel | null>(null);

  constructor(
    router: Router,
    location: Location,
    private countCartService: CountCartService,
    api: CommonApiService,
    service: CollectionService<Hotel>,
    private toastr: ToastrService,
    private route: ActivatedRoute,
    private apiAuth: AuthenticationApiService,
    @Inject('AuthService')
    public authService: ModelService<Hotel>,
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
    );
  }

  ngOnInit(): void {
    super.ngOnInit();
    this.checkRole();
    const subscribe1 = this.service.clear$
      .pipe(takeUntil(this.destroy$))
      .subscribe(options => {
        this.statusControl.patchValue('0', {
          emitEvent: false,
        });
      });
    this.unsubscribe.push(subscribe1);
  }


  checkRole() {
    if (this.authenticationService?.authService?.model) {
      const user = this.authenticationService?.authService?.model;
      const roleNames = map(user.roles, r => r.name);
      this.roleNames = roleNames;
    }
  }


  goToDetails(data: Hotel) {
    this.router
      .navigate([`detail/${data.id}`], { relativeTo: this.route })
      .then();
  }



}
