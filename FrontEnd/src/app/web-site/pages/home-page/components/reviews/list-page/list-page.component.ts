import { BehaviorSubject, takeUntil } from 'rxjs';
import { Component, Inject } from '@angular/core';
import Swal, { SweetAlertResult } from 'sweetalert2';
import { getMenuByRole, getRouteByRole } from '@functions/routing';

import { ActivatedRoute } from '@angular/router';
import { ApiResponse } from '@models/common/api-response.model';
import { AuthenticationApiService } from '@services/account/authentication-api.service';
import { AuthenticationService } from '@services/account/authentication.service';
import { CollectionComponent } from '@components/abstract/collection.component';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { FormControl } from '@angular/forms';
import { Location } from '@angular/common';
import { MenuItem } from '@models/layout/menu.model';
import { ModelService } from '@services/common/model.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Options } from 'ng5-slider';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Review } from '@models/reviews/review.model';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { User } from '@models/account/user.model';
import { alertFire } from '@functions/alerts';

@Component({
  selector: 'app-reviews-list-page',
  templateUrl: './list-page.component.html',
  styleUrls: ['./list-page.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'reviews' },
    CommonApiService,
  ],
})
export class ReviewsListPageComponent extends CollectionComponent<Review> {
  breadCrumbs = [
    { label: 'Reseñas', active: true },
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

  private subject$: BehaviorSubject<Review | null> =
    new BehaviorSubject<Review | null>(null);

  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<Review>,
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
  }
}
