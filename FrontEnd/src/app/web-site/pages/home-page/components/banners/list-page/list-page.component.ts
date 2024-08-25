import { ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '@services/account/authentication.service';
import { Banner } from '@models/banners/banner.model';
import { BehaviorSubject } from 'rxjs';
import { CollectionComponent } from '@components/abstract/collection.component';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { Component } from '@angular/core';
import { Location } from '@angular/common';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-banners-list-page',
  templateUrl: './list-page.component.html',
  styleUrls: ['./list-page.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'banners' },
    CommonApiService,
  ],
})
export class ListPageComponent  extends CollectionComponent<Banner> {
  breadCrumbs = [
    { label: 'Banners', active: true },
  ];
  TRANSLATE_KEY= 'ADMIN.USERS.PAGES.LIST.'


  carouselOption: OwlOptions = {
    items: 1,
    loop: true,
    margin: 40,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    nav: false,
    dots: true,
  };

  private subject$: BehaviorSubject<Banner | null> =
    new BehaviorSubject<Banner | null>(null);

  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<Banner>,
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
    );
  }

  ngOnInit(): void {
    super.ngOnInit();
  }
}
