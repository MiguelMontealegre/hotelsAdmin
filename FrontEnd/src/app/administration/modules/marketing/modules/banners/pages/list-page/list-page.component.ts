
import { Component, Inject } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { CollectionComponent } from '@components/abstract/collection.component';
import { User } from '@models/auth.models';
import { MenuItem } from '@models/layout/menu.model';
import { Banner } from '@models/banners/banner.model';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AuthenticationApiService } from '@services/account/authentication-api.service';
import { AuthenticationService } from '@services/account/authentication.service';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { ModelService } from '@services/common/model.service';
import { Lightbox } from 'ngx-lightbox';
import { ToastrService } from 'ngx-toastr';
import { Location } from '@angular/common';
import { FormControl } from '@angular/forms';
import { takeUntil } from 'rxjs';
import Swal, { SweetAlertResult } from 'sweetalert2';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
@Component({
  selector: 'app-list-page',
  templateUrl: './list-page.component.html',
  styleUrls: ['./list-page.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'banners' },
    CommonApiService,
    CommonVerbsApiService
  ],
})
export class ListPageComponent   extends CollectionComponent<Banner>{
  breadCrumbs = [
    { label: 'Promociones', active: true },
  ];
  statusControl = new FormControl('0');
  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    private  api2: CommonVerbsApiService,
    service: CollectionService<Banner>,
    private toastr: ToastrService,
    private route: ActivatedRoute,
    private apiAuth: AuthenticationApiService,
    @Inject('AuthService')
    public authService: ModelService<User>,
    @Inject('MenuService')
    public menuService: ModelService<MenuItem[]>,
    private modal: NgbModal,
    public authenticationService: AuthenticationService,
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
    const subscribe = this.statusControl.valueChanges
      .pipe(takeUntil(this.destroy$))
      .subscribe(value => {
        const endValue = [value !== '0'];
        this.service.setFilterValue({
          key: 'archivedInd',
          values: endValue,
        });
      });
    this.unsubscribe.push(subscribe);

    const subscribe1 = this.service.clear$
      .pipe(takeUntil(this.destroy$))
      .subscribe(options => {
        this.statusControl.patchValue('0', {
          emitEvent: false,
        });
      });
    this.unsubscribe.push(subscribe1);
  }
  delete(id: string) {
    Swal.fire({
      title: '¿Estás seguro de que quieres eliminar?',
      text: "No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#07B59A',
      cancelButtonColor: '#f46a6a',
      confirmButtonText: 'Si, Eliminar!',
      cancelButtonText: 'Cancelar',
    }).then((result: SweetAlertResult) => {
      if (result.value) {
        this.api2.delete(`banners/${id}`).subscribe(
          () => {
            this.toastr.success('Cambios aplicados.');
            this.service.init(this.service.options);
          },
          e => {
            this.toastr.error(e?.error.message || 'Ocurrió un error');
          }
        );
      }
    });
  }
}