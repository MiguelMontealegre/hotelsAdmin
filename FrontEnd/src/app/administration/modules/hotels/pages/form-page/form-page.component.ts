import { Component, Inject, OnInit } from "@angular/core";

import { CommonApiService } from "@services/common/common-api.service";
import { CommonPageComponent } from "@components/abstract/common-page.component";
import { ModelService } from "@services/common/model.service";
import { map } from 'rxjs/operators';
import { ParamMap } from '@angular/router';
import { of } from 'rxjs';
import { switchMap } from 'rxjs';
import { validate as uuidValidate } from 'uuid';
import { takeUntil } from 'rxjs/operators';
import { tap } from 'rxjs';
import { ActivatedRoute } from "@angular/router";
import { Hotel } from "@models/hotels/hotel.model";

@Component({
  selector: "app-forms-page",
  templateUrl: "./form-page.component.html",
  styleUrls: ["./form-page.component.scss"],
  providers: [
    { provide: 'API_SERVICE', useValue: 'hotels' },
    CommonApiService,
    {
      provide: 'HotelService',
      useFactory: () => new ModelService<Hotel>(),
    },
  ],
})
export class FormPageComponent extends CommonPageComponent implements OnInit {
  constructor(
    private api: CommonApiService,
    @Inject('HotelService')
    public hotelService: ModelService<Hotel>,
    private route: ActivatedRoute
  ) {
    super('Hotel', [
      { label: 'Hotels', route: '../' },
    ]);
  }

  ngOnInit(): void {
    const subscribe = this.route.paramMap
      .pipe(
        map((params: ParamMap) => {
          if (params.get('id') && uuidValidate(params.get('id') || '')) {
            return params.get('id');
          }
          return null;
        }),
        tap(id => {
          if (id) {
            this.breadCrumbs.push({ label: 'Editar', active: true });
          } else {
            this.breadCrumbs.push({ label: 'Crear', active: true });
          }
          this.hotelService.isLoading = true;
        }),
        switchMap(id => {
          return id
            ? this.api.get<Hotel>(`/${id}`, { limit: 50, page: 1 },)
            : of(null);
        }),
        takeUntil(this.destroy$)
      )
      .subscribe(model => {
        this.hotelService.isLoading = false;
        this.hotelService.set(model);
      });
    this.unsubscribe.push(subscribe);
  }
}
