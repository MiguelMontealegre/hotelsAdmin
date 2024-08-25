import { Component, Inject, OnInit } from '@angular/core';
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
import { CommonVerbsApiService } from 'src/app/core/services/common/common-verbs-api.service';

import { ProductPromotion } from '@models/promotion/promotion-pop-up.model';
@Component({
  selector: 'app-custom-popup',
  templateUrl: './custom-popup.component.html',
  styleUrls: ['./custom-popup.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: 'pop-up' },
    CommonVerbsApiService,
    CommonApiService,
    {
      provide: 'PromotionService',
      useFactory: () => new ModelService<ProductPromotion>(),
    },
  ],
})
export class CustomPopupComponent extends CommonPageComponent implements OnInit {
  constructor(
    private api: CommonVerbsApiService,
    @Inject('PromotionService')
    public promotionService: ModelService<ProductPromotion>,
    private route: ActivatedRoute
  ) {
    super('Product', [
      { label: 'Products', route: '../' },
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
          this.promotionService.isLoading = true;
        }),
        switchMap(id => {
          return id
            ? this.api.get<ProductPromotion>(`promotions/${id}`, { limit: 50, page: 1 },)
            : of(null);
        }),
        takeUntil(this.destroy$)
      )
      .subscribe(model => {
        this.promotionService.isLoading = false;
        this.promotionService.set(model);
      });
    this.unsubscribe.push(subscribe);
  }
}
