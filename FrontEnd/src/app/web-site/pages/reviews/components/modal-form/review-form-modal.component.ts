import { AfterViewInit, Component, OnInit } from '@angular/core';
import { EventEmitter, Output } from '@angular/core';
import { UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';

import { AuthenticationService } from '@services/account/authentication.service';
import { CommonApiService } from '@services/common/common-api.service';
import { Input } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { Observable } from 'rxjs';
import { Review } from '@models/reviews/review.model';
import { ToastrService } from 'ngx-toastr';
import { get } from 'lodash';

@Component({
  selector: 'app-review-form-modal',
  templateUrl: './review-form-modal.component.html',
  styleUrls: ['./review-form-modal.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: 'reviews' },
    CommonApiService,
  ],
})
export class ReviewFormModalComponent implements OnInit {
  @Output() response: EventEmitter<boolean> = new EventEmitter<boolean>();
  @Input() review: Review = null;

  group: UntypedFormGroup;
  submit = false;

  constructor(
    public activeModal: NgbActiveModal,
    public api: CommonApiService,
    private builder: UntypedFormBuilder,
    private toastr: ToastrService,
    public authenticationService: AuthenticationService,
  ) {
    this.group = this.builder.group({
      id: [null],
      title: ['', Validators.required],
      content: ['', [Validators.required]],
      valoration: [null, [Validators.required]],
    });
  }

  ngOnInit(): void {
  }


  get f() {
    return this.group.controls;
  }

  ngClassValidate(group: UntypedFormGroup, name: string): string {
    if (!this.submit) return '';
    return group.controls[name].errors ? 'is-invalid' : 'is-valid';
  }

  ngSubmit(): void {
    this.submit = true;
    if (this.group.valid) {
      const body = this.group.getRawValue();
      body.userId = this.authenticationService.authService.model.id;
      const id = get(body, 'id', null);
      let subscribe: Observable<any>;
      let path = '/';
      if (id !== null) {
        path += `${id}`;
        subscribe = this.api.put<Review>(path, body);
      } else {
        subscribe = this.api.post<Review>(path, body);
      }
      subscribe.subscribe({
        complete: () => (this.submit = false),
        error: err => {
          this.toastr.error(
            err?.error?.message || err?.message || 'Ocurrió un error.'
          );
        },
        next: response2 => {
          this.toastr.success('Cambios Aplicados.');
          this.group.reset();
          this.eventResponse(true);
        },
      });
    } else {
      this.toastr.error('Campos inválidos .');
    }
  }


  eventResponse(value: boolean) {
    this.response.emit(value);
    this.activeModal.dismiss();
  }

  valoration: number;
  addValoration(val: number){
    this.valoration = val;
    this.group.patchValue({
      valoration: val
    });
  }

}



