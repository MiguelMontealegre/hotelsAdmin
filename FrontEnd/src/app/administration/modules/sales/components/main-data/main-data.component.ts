import * as _ from 'lodash';
import * as moment from 'moment'

import { ChangeDetectorRef, Component } from '@angular/core';
import { forEach, forOwn, map, orderBy } from 'lodash';

import { Analytics } from '@models/charts/analytics.model';
import { AnalyticsFilter } from '@models/common/analytics-filter.model';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonComponent } from '@components/abstract/common-component.component';
import { Input } from '@angular/core';
import { ModelService } from '@services/common/model.service';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { User } from '@models/account/user.model';

@Component({
  selector: 'app-main-data',
  templateUrl: './main-data.component.html',
  providers: [
    { provide: 'API_SERVICE', useValue: 'analytics' },
    CommonApiService,
    NgbActiveModal,
  ],
})
export class MainDataComponent extends CommonComponent implements OnInit {
  @Input() category: string;
  @Input() type: string;
  @Input() title: string;
  @Input() user: User;
  @Input() productId: string;

  page = 1;
  pageSize = 10;

  arrData: any = [];
  EndpointParams: any = [];
  arrTableType: any[] = [];
  arrTableTypeLabels: any[] = [];
  mainData = {
    totalSales: 0,
  };

  private default: any = {
    series: [
      {
        name: '',
        data: [],
      },
    ],
    colors: ['#556ee6', '#f1b44c', '#34c38f'],
    chart: {
      height: 250,
      type: 'area',
      toolbar: {
        show: false,
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: 'smooth',
    },
    xaxis: {
      type: 'datetime',
      min: undefined,
      max: undefined,
      tickAmount: 6,
      labels: {
        format: 'dd MMM',
      }
    },
    tooltip: {
      x: {
        format: 'dd MMM yyyy',
      },
    },
    fill: {},
    grid: {},
    responsive: [],
    yaxis: {},
    plotOptions: {},
  };
  public options: any;

  countSubsChanges = 0;
  analyticsFilter: AnalyticsFilter | null = {};
  filterOptions = ['dateRange'];

  constructor(
    private api: CommonApiService,
    public modal: NgbModal,
    public analyticsFilterService: ModelService<AnalyticsFilter>,
    private toastr: ToastrService,
  ) {
    super();
  }

  ngOnInit(): void {
    this.options = this.default;
    const subscribe = this.analyticsFilterService.model$.subscribe(filter => {
      this.analyticsFilter = filter;
      if (!this.analyticsFilter?.clearFilters) {
        this.countSubsChanges++;
        for (const i in this.filterOptions) {
          this.subscribeFilter(this.filterOptions[i]);
        }
      }
      if (this.analyticsFilter?.groupByFilter) {
        this.EndpointParams['groupBy'] = this.analyticsFilter?.groupByFilter;
      } else {
        this.EndpointParams['groupBy'] = 'day';
      }

      if (this.analyticsFilter?.serviceTypeFilter) {
        this.EndpointParams['serviceType'] = this.analyticsFilter?.serviceTypeFilter;
        if (this.analyticsFilter?.serviceTypeFilter === 'all') {
          delete this.EndpointParams['serviceType'];
        }
      } else {
        delete this.EndpointParams['serviceType'];
      }

      if (this.analyticsFilter?.clearFilters) {
        this.EndpointParams = [];
        this.options.tooltip.x.format = 'dd MMM yyyy';
      }
      this.getData();
    });
    this.unsubscribe.push(subscribe);
    this.getData();
  }

  subscribeFilter(filterName: string) {
    let paramSubscription: string[] | any = [];
    if (filterName == 'dateRange' && this.analyticsFilter?.dateFilter) {
      paramSubscription = this.analyticsFilter?.dateFilter;
    }

    for (let i = 0; i <= this.countSubsChanges; i++) {
      delete this.EndpointParams[`${filterName}[${i}]`];
    }

    if (paramSubscription) {
      paramSubscription.forEach((e: { id: string }, index: number) => {
        this.EndpointParams[`${filterName}[${index}]`] = e;
      });
    }
    paramSubscription = [];
  }


  //=====================MAIN DATA
  getData() {
    this.EndpointParams['type'] = this.category;
    this.EndpointParams['userId']  = this.user?.id
    this.EndpointParams['limit'] = 10;
    if (!this.EndpointParams?.groupBy) {
      this.EndpointParams['groupBy'] = 'day';
    }
    if (this.productId) {
      this.EndpointParams['productId'] = this.productId;
    }
    const route = this.productId ? `sales` : '';


    this.api
      .get<Analytics>(`/`+route, this.EndpointParams)
      .subscribe(resp => {
        this.arrData = resp;
        this.mainData.totalSales = resp.totalSales;
        this.buildChart(resp.data);
      },
        err => {
          this.toastr.error(err.message ?? 'Ocurrió un error');
        });
  }

  //=====================BUILD CHART
  buildChart(mainData: Analytics['data']) {
      const series = [];
      forEach(mainData, r => {
        if (r.data.length > 0) {
          const seriesData = {
            name: r.key,
            data: [],
          };
          r.data = r.data.sort((a, b) => {
            if (a.date.includes('/') && this.EndpointParams?.groupBy === 'month') {
              let parts = a.date.split('/');
              let year = parseInt(parts[0]);
              let month = parseInt(parts[1]) - 1;
              let dateA = new Date(year, month).getTime();
              let dateB = new Date(year, month).getTime();
              return dateA - dateB;
            } else {
              let dateA = new Date(a.date).getTime();
              let dateB = new Date(b.date).getTime();
              return dateA - dateB;
            }
          });
          forEach(r.data, r2 => {
            if (r2.date.includes('/') && this.EndpointParams?.groupBy === 'month') {
              let parts = r2.date.split('/');
              let year = parseInt(parts[0]);
              let month = parseInt(parts[1]) - 1;
              seriesData.data.push([new Date(year, month).getTime(), r2.totalValue]);
            } else if (this.EndpointParams?.groupBy === 'day') {
              seriesData.data.push([new Date(r2.date).getTime(), r2.totalValue]);
            }
          })
          series.push(seriesData);
        }
      });
      let aux = JSON.parse(JSON.stringify(this.options));
      if (this.EndpointParams?.groupBy === 'month') {
        aux.tooltip.x.format = 'MMM yyyy';
        aux.xaxis.labels.format = 'MMM yyyy';
        this.options = {...this.options, ...aux};
      } else if(this.EndpointParams?.groupBy === 'day') {
        aux.tooltip.x.format = 'dd MMM yyyy';
        aux.xaxis.labels.format = 'dd MMM yyyy';
        this.options = {...this.options, ...aux};
      }
      this.options.series = series;
  }
}
