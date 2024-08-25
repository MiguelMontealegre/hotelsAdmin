import { NgbDate, NgbDateAdapter } from '@ng-bootstrap/ng-bootstrap';

import { CommonApiService } from '@services/common/common-api.service';
import { Component } from '@angular/core';
import { CustomDateAdapter } from '@utils/custom-date-adapter';
import { CustomDateParserUsFormatter } from '@utils/custom-date-parser-formatter';
import { EventEmitter } from '@angular/core';
import { NgbDateParserFormatter } from '@ng-bootstrap/ng-bootstrap';
import { NgbInputDatepicker } from '@ng-bootstrap/ng-bootstrap';
import { Output } from '@angular/core';

@Component({
  selector: 'app-filters',
  templateUrl: './filters.component.html',
  providers: [
    { provide: 'API_SERVICE', useValue: '' },
    CommonApiService,
    { provide: NgbDateParserFormatter, useClass: CustomDateParserUsFormatter },
    { provide: NgbDateAdapter, useClass: CustomDateAdapter },
  ],
})
export class FiltersComponent {
  @Output() clearFiltersEmit = new EventEmitter();
  @Output() dateFiltersEmit = new EventEmitter();
  @Output() groupByFilterEmit = new EventEmitter();
  @Output() seriviceTypeFilterEmit = new EventEmitter();
  hoveredDate: NgbDate | null = null;
  fromDate: NgbDate | null = null;
  toDate: NgbDate | null = null;
  groupByFilter: string = 'day';
  seriviceTypeFilter: string = 'all';
  groupByItems = [
    {
      value: 'day',
      label: 'Dias'
    },
    {
      value: 'month',
      label: 'Meses'
    }
  ];
  serviceTypeItems = [
    {
      value: 'all',
      label: 'All Services'
    },
    {
      value: 'regular-response',
      label: 'By Regular Responses'
    },
    {
      value: 'stream-response',
      label: 'By Stream Responses'
    },
    {
      value: 'media-generator',
      label: 'By Media Generator Service'
    },
    {
      value: 'fine-tuning',
      label: 'By Fine Tuning Service'
    }
  ];

  constructor(
    public formatter: NgbDateParserFormatter,
    private customAdapter: CustomDateAdapter
  ) {}

  onDateSelection(date: NgbDate, datepicker: NgbInputDatepicker) {
    if (!this.fromDate && !this.toDate) {
      this.fromDate = date;
    } else if (
      this.fromDate &&
      !this.toDate &&
      date &&
      date.after(this.fromDate)
    ) {
      this.toDate = date;
      this.dateFiltersEmit.emit([
        this.customAdapter.ngbDateToMomentFormat(this.fromDate),
        this.customAdapter.ngbDateToMomentFormat(this.toDate),
      ]);
      datepicker.close();
    } else {
      this.toDate = null;
      this.fromDate = date;
    }
  }

  isHovered(date: NgbDate) {
    return (
      this.fromDate &&
      !this.toDate &&
      this.hoveredDate &&
      date.after(this.fromDate) &&
      date.before(this.hoveredDate)
    );
  }

  isInside(date: NgbDate) {
    return this.toDate && date.after(this.fromDate) && date.before(this.toDate);
  }

  isRange(date: NgbDate) {
    return (
      date.equals(this.fromDate) ||
      (this.toDate && date.equals(this.toDate)) ||
      this.isInside(date) ||
      this.isHovered(date)
    );
  }

  clearFilters() {
    this.clearFiltersEmit.emit(true);
    this.fromDate = null;
    this.toDate = null;
    this.groupByFilter = 'day';
    this.seriviceTypeFilter = 'all';
  }


  changeFilter(arg: Event, filter: string) {
    if (arg && arg.type == 'change') return;
    if (filter === 'groupBy') {
      this.groupByFilterEmit.emit(this.groupByFilter);
    } else if(filter === 'serviceType') {
      this.seriviceTypeFilterEmit.emit(this.seriviceTypeFilter)
    }
  }
}
