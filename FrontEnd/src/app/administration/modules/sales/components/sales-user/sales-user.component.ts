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
@Component({
  selector: 'app-sales-user',
  templateUrl: './sales-user.component.html',
  styleUrls: ['./sales-user.component.scss'],
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'users' },
    CommonApiService,
  ],
})
export class SalesUserComponent   extends CollectionComponent<User>{



  breadCrumbs = [
    { label: 'Admin', active: true },
    { label: 'Users', active: true },
  ];
  statusControl = new FormControl('0');
TRANSLATE_KEY= 'ADMIN.USERS.PAGES.LIST.'
  private subject$: BehaviorSubject<User | null> =
    new BehaviorSubject<User | null>(null);
    graphicParams = [
      {
        chart: 'VENTAS',
        type: 'line',
        title: 'Ventas',
      },
    ];

    roleMapping = {
      'ADMIN': 'Administrador',
      'MARKETER_USER': 'Marketing',
      'SALE_USER': 'Vendedor',
      'SINGLE_USER': 'Usuario Individual',
      'WHOLESALE_USER': 'Mayorista'
    };

    
  isGraphVisible: boolean[] = [false];
  constructor(
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<User>,
    private toastr: ToastrService,
    private route: ActivatedRoute,
    private apiAuth: AuthenticationApiService,
    @Inject('AuthService')
    public authService: ModelService<User>,
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

  getRoleName(role: string): string {
    return this.roleMapping[role] || 'Desconocido';
  }


  deleteUser(id: string) {
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
        this.api.delete(`/${id}`).subscribe(
          () => {
            this.toastr.success('Eliminado correctamente');
            this.service.init(this.service.options);
          },
          e => {
            this.toastr.error(e?.error.message || 'Error');
          }
        );
      }
    });
  }

  goToDetails(data: User) {
    const role = data.roles.find(x => x.name === 'READ_USER');
    if (role && role.companyId) {
      const url = this.router.serializeUrl(
        this.router.createUrlTree([
          `companies/${role.companyId}/members/${data.id}`,
        ])
      );
      window.open(url, '_blank');
      return;
    }
    this.router
      .navigate([`detail/${data.id}`], { relativeTo: this.route })
      .then();
  }

  loginAsThisUser(data: User) {
    this.apiAuth.loginById({ userId: data.id }).subscribe(response => {
      const data = response.data;
      localStorage.clear();
      localStorage.setItem('userToken', data?.plainTextToken);
      localStorage.setItem('tokenIdentifier', data?.accessToken?.tokenable_id);
      this.authService.set(data.user);
      this.menuService.set(getMenuByRole(data.user));
      this.subject$.next(data.user);
      this.router.navigate([getRouteByRole(data.user)]).then();
    });
  }


  toggleGraphVisibility(index: number): void {
    this.isGraphVisible[index] = !this.isGraphVisible[index];
  }
}
