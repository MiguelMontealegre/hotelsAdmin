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
import { Banner } from '@models/banners/banner.model';
@Component({
  selector: 'app-form-page',
  templateUrl: './form-page.component.html',
  styleUrls: ['./form-page.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: 'banners' },
    CommonVerbsApiService,
    CommonApiService,
    {
      provide: 'bannerService',
      useFactory: () => new ModelService<Banner>(),
    },
  ],
})
export class FormPageComponent extends CommonPageComponent implements OnInit {
  constructor(
    private api: CommonVerbsApiService,
    @Inject('bannerService')
    public bannerService: ModelService<Banner>,
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
          this.bannerService.isLoading = true;
        }),
        switchMap(id => {
          return id
            ? this.api.get<Banner>(`banners/${id}`, { limit: 50, page: 1 },)
            : of(null);
        }),
        takeUntil(this.destroy$)
      )
      .subscribe(model => {
        this.bannerService.isLoading = false;
        this.bannerService.set(model);
      });
    this.unsubscribe.push(subscribe);
  }
}