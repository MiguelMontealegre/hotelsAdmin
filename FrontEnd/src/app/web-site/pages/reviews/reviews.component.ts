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
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { Review } from '@models/reviews/review.model';
import { ReviewFormModalComponent } from './components/modal-form/review-form-modal.component';
import Swal, { SweetAlertResult } from 'sweetalert2';
import { map } from 'lodash';

@Component({
  selector: 'app-reviews',
  templateUrl: './reviews.component.html',
  styleUrls: ['./reviews.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'reviews' },
    CommonApiService,
    CommonVerbsApiService
  ],
})
export class ReviewsComponent extends CollectionComponent<Review> {
  statusControl = new FormControl('0');
  TRANSLATE_KEY = 'ADMIN.USERS.PAGES.LIST.'
  discountControl = new FormControl(null);
  roleNames: string[] = [];

  private subject$: BehaviorSubject<Review | null> =
    new BehaviorSubject<Review | null>(null);

  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<Review>,
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
    this.getReviewsMetaData();
    if (this.authenticationService?.authService?.model) {
      this.checkRole();
    }
  }


  checkRole(){
    const user = this.authenticationService?.authService?.model;
    const roleNames = map(user.roles, r => r.name);
    this.roleNames = roleNames;
  }


  openModalForm() {
    if (this.authenticationService.authService.model) {
      const modalRef = this.modal.open(ReviewFormModalComponent, { size: 'lg', });
      modalRef.componentInstance.response.subscribe((response) => {
        if (response) {
          this.clear();
          this.getReviewsMetaData();
        }
      });
    } else {
      this.router
        .navigate([`/account/auth/login-2`])
        .then();
    }
  }


  deleteReview(id: string) {
    Swal.fire({
      title: 'Estás seguro de que quieres eliminar?',
      text: "¡No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#07B59A',
      cancelButtonColor: '#f46a6a',
      confirmButtonText: 'Si, Eliminar!',
      cancelButtonText: 'Cancelar',
    }).then((result: SweetAlertResult) => {
      if (result.value) {
        this.api.delete(`/${id}`).subscribe(
          () => {
            this.toastr.success('Cambios Aplicados');
            this.service.init(this.service.options);
            this.getReviewsMetaData();
          },
          e => {
            this.toastr.error(e?.error.message || 'Error');
          }
        );
      }
    });
  }


  pinReview(data: Review) {
    Swal.fire({
      title: 'Estás seguro?',
      text: "¡Estos cambios podran revertirse en la pagina actual!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#07B59A',
      cancelButtonColor: '#f46a6a',
      confirmButtonText: 'Si, Pin!',
      cancelButtonText: 'Cancelar',
    }).then((result: SweetAlertResult) => {
      if (result.value) {
        this.api.put(`/${data.id}`, {pin: !data.pin}).subscribe(
          () => {
            this.toastr.success('Cambios Aplicados');
            this.service.init(this.service.options);
            this.getReviewsMetaData();
          },
          e => {
            this.toastr.error(e?.error.message || 'Error');
          }
        );
      }
    });
  }


  stars1: number;
  stars2: number;
  stars3: number;
  stars4: number;
  stars5: number;
  getReviewsMetaData() {
    this.api
      .get<{ data: any }>(
        '/metadata',
      )
      .subscribe(r => {
        this.stars1 = r.data.stars1;
        this.stars2 = r.data.stars2;
        this.stars3 = r.data.stars3;
        this.stars4 = r.data.stars4;
        this.stars5 = r.data.stars5;
      });
  }


}

