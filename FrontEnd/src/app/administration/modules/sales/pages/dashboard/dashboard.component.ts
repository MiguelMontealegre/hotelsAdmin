import { Component, OnInit, ViewChild } from '@angular/core';
import { User } from '@models/auth.models';
import { AnalyticsFilter } from '@models/common/analytics-filter.model';
import { NgbDateAdapter } from '@ng-bootstrap/ng-bootstrap';
import { AuthenticationService } from '@services/auth.service';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { ModelService } from '@services/common/model.service';
import { CustomDateAdapter } from '@utils/custom-date-adapter';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  providers: [
    ModelService,
    {
      provide: 'API_SERVICE',
      useValue: 'analytics',
    },
    CommonApiService,
    CommonVerbsApiService,
    { provide: NgbDateAdapter, useClass: CustomDateAdapter},
  ],
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {
  @ViewChild('scrollRef') scrollRef;
  breadCrumbItems: Array<{}>;
  graphicParams = [
    {
      chart: 'VENTAS',
      type: 'line',
      title: 'Ventas',
    },
  ];
  user: User;
  analyticsFilter: AnalyticsFilter | null = {};
  betweenDateprice: number;
  price: number;
  totalSales: number;
  dateFilter: string;
  countSubsChanges = 0;
  EndpointParams: any = [];


  constructor(
      private authenticationService: AuthenticationService,
      private api: CommonApiService,
      private toastr: ToastrService,
      private api2: CommonVerbsApiService,
      public analyticsFilterService: ModelService<AnalyticsFilter>
    ) { }


  ngOnInit(): void {
    this.breadCrumbItems = [{ label: 'Dashboards'  ,active: true }, { label: 'Ventas', active: true }];
    this.getPrice();
    this.analyticsFilterService.model$.subscribe(filter => {
      if (filter?.clearFilters) {
        this.EndpointParams = [];
      }
      if(filter.dateFilter){
        this.countSubsChanges++;
        let paramSubscription: string[] | any = [];
        paramSubscription = filter.dateFilter;
        for (let i = 0; i <= this.countSubsChanges; i++) {
          delete this.EndpointParams[`${'dateRange'}[${i}]`];
        }
        if (paramSubscription) {
          paramSubscription.forEach((e: string, index: number) => {
            this.EndpointParams[`${'dateRange'}[${index}]`] = e;
          });
        }
        paramSubscription = [];
      }
      this.getPrice();
    });
  }

  getPrice(){
    this.api2.get<any>('analytics/price', this.EndpointParams).subscribe(r => {
      this.betweenDateprice = r.betweenDateprice;
      this.price = r.price;
      this.totalSales =  r.total;
    },
    err => {
      this.toastr.warning('No API service has been generated yet')
    })
  } 


  filterArray(arrName: string, value: any) {
    if (this.analyticsFilter) {
      if (arrName == 'dateRange') {
        this.analyticsFilter.dateFilter = value;
      } else if(arrName == 'groupBy') {
        this.analyticsFilter.groupByFilter = value;
      } else if(arrName == 'serviceType'){
        this.analyticsFilter.serviceTypeFilter = value;
      }
      delete this.analyticsFilter['clearFilters'];
      this.analyticsFilterService.set(this.analyticsFilter);
    }
  }

  clearFiltersEmit(state: boolean) {
    this.analyticsFilter = {};
    this.analyticsFilter.clearFilters = state;
    this.analyticsFilterService.set(this.analyticsFilter);
  }


  scrollToElement(id: string) {
    const el = document.getElementById(id);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' });
    }
  }

}
