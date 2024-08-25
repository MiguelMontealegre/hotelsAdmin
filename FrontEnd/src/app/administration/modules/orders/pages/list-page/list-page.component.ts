import { ActivatedRoute, Router } from '@angular/router';

import { ApiResponse } from '@models/common/api-response.model';
import { AuthenticationService } from '@services/account/authentication.service';
import { CollectionComponent } from '@components/abstract/collection.component';
import { CollectionService } from '@services/common/collection.service';
import { CommonApiService } from '@services/common/common-api.service';
import { Component } from '@angular/core';
import { Location } from '@angular/common';
import { Order } from '@models/orders/order.model';
import Swal from 'sweetalert2';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-list-page',
  templateUrl: './list-page.component.html',
  providers: [
    CollectionService,
    { provide: 'API_SERVICE', useValue: 'orders' },
    CommonApiService,
  ],
})
export class ListPageComponent extends CollectionComponent<Order> {
  breadCrumbs = [
    { label: 'Ordenes', active: true },
  ];

  statusItems = [
    {
      label: 'Reservado',
      value: 'RESERVED'
    },
    {
      label: 'Despachado',
      value: 'DISPATCHED'
    },
    {
      label: 'Finalizado',
      value: 'FINISHED'
    }
  ];

  TRANSLATE_KEY= 'MODEL_BOTS.MODULES.CRON-JOBS.PAGES.LIST-PAGE.';

  constructor(
    private route: ActivatedRoute,
    router: Router,
    location: Location,
    api: CommonApiService,
    service: CollectionService<Order>,
    private toastr: ToastrService,
    public authenticationService: AuthenticationService,
  ) {
    super(router, location, ``, api, service, 10, 'createdAt', [], []);
  }
  editingStatus = false;
  editingIndex: number;
  editingData: any;
  originalStatus: string; // Variable para almacenar el estado original

  editStatus(data: Order ,index: number) {
    this.editingStatus = true;
    this.editingIndex = index;
    this.editingData = { ...data };
    this.originalStatus = data.status// Almacena el estado original al iniciar la edición
  }

  cancelEdit() {
    this.editingStatus = false;
  }

  saveStatus(data: Order) {
    if (data.status === this.originalStatus) {
      this.toastr.warning('No se realizaron cambios.');
      return;
    }

    this.api.put(`/${data.id}`, data).subscribe(
      response => {
        this.toastr.success('Cambios Aplicados.');
        this.editingStatus = false;
      },
      error => {
        this.toastr.error(error?.error?.message || 'Ocurrió un error.');
        this.editingStatus = false;
        this.clear();
      }
    );

    this.editingStatus = false;
  }

  delete(model: Order) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: `¡Esta acción eliminará de los pedidos!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#07B59A',
      cancelButtonColor: '#f46a6a',
      confirmButtonText: 'Confirmar',
    }).then(result => {
      if (result.value) {
        this.api.delete<ApiResponse>(`/${model.id}`).subscribe(
          orderDeletion => {
            this.clear();
            this.toastr.success(orderDeletion?.message || 'Cambios Aplicados.');
          },
          orderDeletion => {
            this.toastr.error(
              orderDeletion?.error.message || 'Ocurrió un error.'
            );
          }
        );
      }
    });
  }
}
