import { ActivatedRoute } from '@angular/router';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonPageComponent } from '@components/abstract/common-page.component';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { Component } from '@angular/core';
import { Inject } from '@angular/core';
import { ModelService } from '@services/common/model.service';
import { OnInit } from '@angular/core';
import { ParamMap } from '@angular/router';
import { filter } from 'rxjs/operators';
import { map } from 'rxjs/operators';
import { switchMap } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { tap } from 'rxjs';
import { validate as uuidValidate } from 'uuid';
import { Product } from '@models/products/product.model';

@Component({
  selector: 'app-detail-page',
  templateUrl: './detail-page.component.html',
  providers: [
    { provide: 'API_SERVICE', useValue: 'products' },
    CommonApiService,
    CommonVerbsApiService,
    ModelService,
  ],
})
export class DetailPageComponent extends CommonPageComponent implements OnInit {
  active = 1;

  constructor(
    private api: CommonApiService,
    @Inject('ProductService')
    public productService: ModelService<Product>,
    private route: ActivatedRoute
  ) {
    super('Productos', [
      { label: 'Productos', active: false, route: './../../' },
      { label: 'Detalle', active: true },
    ]);
  }

  ngOnInit(): void {
    const subscribe = this.route.paramMap
      .pipe(
        map((params: ParamMap) => {
          if (params.get('id') && uuidValidate(params.get('id') || '')) {
            const actionLabel = 'Detalle';
            this.breadCrumbs = [
              { label: 'Productos', active: false, route: './../../' },
              { label: actionLabel, active: true },
            ];
            return params.get('id');
          }
          return null;
        }),
        filter(i => i !== null),
        tap(() => (this.productService.isLoading = true)),
        switchMap(id => this.api.get<Product>(`/${id}`, {}, [])),
        takeUntil(this.destroy$)
      )
      .subscribe((model: Product) => {
        this.productService.isLoading = false;
        this.productService.set(model);
      });
    this.unsubscribe.push(subscribe);
  }
}
