import { Component, EventEmitter, Input, Output } from '@angular/core';
import { UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';

import { CommonApiService } from '@services/common/common-api.service';
import { CommonComponent } from '@components/abstract/common-component.component';
import { CommonFormComponent } from '@components/abstract/common-form.component';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { ModelService } from '@services/common/model.service';
import { NgbActiveModal, NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ThemeService } from '@services/layout/theme-service.service';
import { ToastrService } from 'ngx-toastr';
import { environment } from '@environments/environment';
import { Order, Passenger } from '@models/orders/order.model';
import { AuthenticationService } from '@services/account/authentication.service';
import { Product } from '@models/products/product.model';

@Component({
  selector: 'app-booking-modal',
  templateUrl: './booking-modal.component.html',
  styleUrls: ['./booking-modal.component.scss'],
  providers: [
    ModelService,
    { provide: 'API_SERVICE', useValue: '' },
    CommonApiService,
    CommonVerbsApiService,
  ],
})
export class BookingModalComponent extends CommonFormComponent<Passenger, Passenger> {
  @Output() bookingSaved: EventEmitter<Order> = new EventEmitter();
  @Input() product: Product;
  bookingGroup: UntypedFormGroup;
  constructor(
    public activeModal: NgbActiveModal,
    builder: UntypedFormBuilder,
    api: CommonApiService,
    toastr: ToastrService,
    private themeService: ThemeService,
    public authenticationService: AuthenticationService,
    private api2: CommonVerbsApiService,
  ) {
    super(builder, api, toastr);
    this.group = this.builder.group({
      date: ['', Validators.required],
      emergencyContactName: ['', [Validators.required]],
      emergencyContactPhone: ['', [Validators.required]],
      passengers: [[]],
    });
    this.bookingGroup = this.builder.group({
      name: ['', Validators.required],
      birthdate: ['', [Validators.required]],
      email: [null, [Validators.required]],
      phone: ['', [Validators.required]],
      idType: ['', [Validators.required]],
      identification: ['', [Validators.required]],
      gender: ['', [Validators.required]],
    });
  }

  genders = [
    'male',
    'female'
  ];

  idTypes = [
    'nationalId',
    'passport'
  ];

  passengerSubmit = false;
  passengers: Passenger[] = [];

  get f() {
    return this.group.controls;
  }

  get bookingF() {
    return this.bookingGroup.controls;
  }

  ngClassValidatePassenger(group: UntypedFormGroup, name: string): string {
    if (!this.passengerSubmit) return '';
    return group.controls[name].errors ? 'is-invalid' : 'is-valid';
  }

  addPassenger(){
    this.passengerSubmit = true;
    if (this.bookingGroup.valid) {
      const body = this.bookingGroup.getRawValue();
      body.uploadByUserId = this.authenticationService.authService.model.id;
      this.group.patchValue({
        passengers: [...this.group.get('passengers').value, body]
      });
      this.passengers.unshift(body);
      this.bookingGroup.reset();
      this.passengerSubmit = false;
    }
  }

  ngSubmitAndPay(provider: string) {
    this.submit = true;
    if (this.group.valid && this.passengers.length > 0) {
      if(provider === 'paypal'){
        this.paypal();
      } else if(provider === 'mercadopago'){
        this.mercadoPago();
      }
    } else {
      this.toastr.error('Please fill all the fields');
    }
  }


  deletePassenger(index: string){
    const passengers = this.group.get('passengers').value;
    passengers.splice(index, 1);
    this.group.patchValue({
      passengers: passengers
    });
  }


  paypal() {
    const body = this.group.getRawValue();
    body.uploadByUserId = this.authenticationService.authService.model.id;
    body.date = this.toDate(this.group.get('date').value);
    body.passengers.map(e => e.birthdate = this.toDate(e.birthdate));

    const params = {
      productId: this.product.id,
      userId: this.authenticationService.authService.model.id,
      billingDetails: body,
    }
    this.api2.post<any>('payments/paypal', params).subscribe(r => {
      window.location.href = r;
    });
  }

  mercadoPago() {
    const body = this.group.getRawValue();
    body.uploadByUserId = this.authenticationService.authService.model.id;
    body.date = this.toDate(this.group.get('date').value);
    body.passengers.map(e => e.birthdate = this.toDate(e.birthdate));

    const params = {
      productId: this.product.id,
      userId: this.authenticationService.authService.model.id,
      billingDetails: body,
    };
    this.api2.post<any>('payments/mercadopago', params).subscribe(r => {
      window.location.href = r.init_point;
    });
  }

  toDate(date: NgbDateStruct): string | null {
    if (!date) {
      return null;
    }

    const jsDate = new Date(date.year, date.month - 1, date.day);

    if (isNaN(jsDate.getTime())) {
      return null;
    }
    const isoDateString = jsDate.toISOString().slice(0, 10);
    return isoDateString;
  }
  fromDateISOString(isoDateString: string): NgbDateStruct | null {
    if (!isoDateString) {
      return null;
    }

    const dateParts = isoDateString.split('-');
    if (dateParts.length !== 3) {
      return null;
    }

    const year = parseInt(dateParts[0], 10);
    const month = parseInt(dateParts[1], 10);
    const day = parseInt(dateParts[2], 10);

    if (isNaN(year) || isNaN(month) || isNaN(day)) {
      return null;
    }


    const ngbDate: NgbDateStruct = { year, month, day };

    return ngbDate;
  }

}


export class NgbdModalComponent {
  constructor(private modalService: NgbModal) { }
}
