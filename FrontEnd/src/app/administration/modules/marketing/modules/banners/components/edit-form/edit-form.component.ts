import { Component, Inject, OnInit } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, UntypedFormBuilder, UntypedFormGroup } from '@angular/forms';
import { Validators } from '@angular/forms';
import { AuthenticationService } from '@services/account/authentication.service';
import { NgxDropzoneChangeEvent } from 'ngx-dropzone';
import { CommonVerbsApiService } from 'src/app/core/services/common/common-verbs-api.service';
import { Router, UrlHandlingStrategy } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { ActivatedRoute } from '@angular/router';
import { Banner } from '@models/banners/banner.model';
import { CommonFormComponent } from '@components/abstract/common-form.component';
import { CommonApiService } from '@services/common/common-api.service';
import { ModelService } from '@services/common/model.service';
import { start } from '@popperjs/core';
import { get } from 'lodash';
import { Observable } from 'rxjs';
import { NgbDatepickerConfig, NgbDateStruct, NgbInputDatepickerConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

import { Media } from '@models/media/media.model';
import { readFile } from '@functions/files';
import { Lightbox } from 'ngx-lightbox';
@Component({
  selector: 'app-edit-form',
  templateUrl: './edit-form.component.html',
  styleUrls: ['./edit-form.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: '' },
    CommonApiService,
    CommonVerbsApiService,
  ],
})
export class EditFormComponent extends CommonFormComponent<Banner, Banner>
  implements OnInit {

  file: any;
  addedFiles: any[] = [];
  fileSelected = false;
  initDataLoaded = false;
  media: Media = null;
  mediaSm: Media = null;
  submit = true;

  constructor(
    private route: ActivatedRoute,
    @Inject('bannerService')
    private bannerervice: ModelService<Banner>,
    private router: Router,
    builder: UntypedFormBuilder,
    private formBuilder: UntypedFormBuilder,
    api: CommonApiService,
    public toastr: ToastrService,
    private modalService: NgbModal,
    public authenticationService: AuthenticationService,
    private service: AuthenticationService,
    private bsDatepickerConfig: NgbInputDatepickerConfig,
    private lightbox: Lightbox
  ) {
    super(builder, api, toastr)

    this.group = this.builder.group({
      id: [null],
      title: [null],
      startDate: [null, [Validators.required,]],
      endDate: [null, [Validators.required,]],
      link: [null, [Validators.required]],
      mediaId: [null, [Validators.required]],
      mediaSmId: [null, [Validators.required]],
    });
  }


  ngOnInit(): void {
    const subscribe = this.bannerervice.model$.subscribe(product => {
      if (product) {
        this.init(product);
      }
      this.initDataLoaded = true;
    });
    this.unsubscribe.push(subscribe);

    const subscribeForm = this.submitEvent.subscribe(model => {
      if (model) {
        this.bannerervice.set(model);
      }
    });
    this.unsubscribe.push(subscribeForm);
  }



  onSelect(event: NgxDropzoneChangeEvent) {
    const file = event.addedFiles[0];
    if (file) {
      readFile(file).then(() => {
        const subscribe = this.api
          .form<Media[]>(`media/upload`, {
            file: file,
            source: 'PRODUCT',
            bytesSize: file.size
          })
          .subscribe(
            r => {
              this.group.patchValue({
                mediaId: r[0].id
              });
              this.media = r[0];
            },
            error => {
              this.toastr.error(error?.error?.message || 'Ocurrió un error.');
            }
          );
        this.unsubscribe.push(subscribe);
      });
    }
  }


  onSelectSm(event: NgxDropzoneChangeEvent) {
    const file = event.addedFiles[0];
    if (file) {
      readFile(file).then(() => {
        const subscribe = this.api
          .form<Media[]>(`media/upload`, {
            file: file,
            source: 'PRODUCT',
            bytesSize: file.size
          })
          .subscribe(
            r => {
              this.group.patchValue({
                mediaSmId: r[0].id
              });
              this.mediaSm = r[0];
            },
            error => {
              this.toastr.error(error?.error?.message || 'Ocurrió un error.');
            }
          );
        this.unsubscribe.push(subscribe);
      });
    }
  }


  get f() {
    return this.group.controls;
  }

  private init(Banner: Banner) {
    const startDate = this.fromDateISOString(Banner.startDate);
    const endDate = this.fromDateISOString(Banner.endDate);
    this.group.patchValue({
      id: Banner.id,
      title: Banner.title,
      startDate: startDate,
      endDate: endDate,
      link: Banner.link,
      mediaId: Banner.media?.id,
      mediaSmId: Banner.mediaSm?.id,
    });
    this.media = Banner.media;
    this.mediaSm = Banner.mediaSm;
  }


  async getFileUrl(file: File): Promise<string> {
    return new Promise<string>((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = (event: any) => {
        resolve(event.target.result);
      };
      reader.onerror = (event: any) => {
        reject(event.target.error);
      };
      reader.readAsDataURL(file);
    });
  }


  isImage(item: { file: File, url: string }): boolean {
    return /^image\//.test(item.file.type);
  }


  removeFile(index: number) {
    this.addedFiles.splice(index, 1);
  }


  deleteData() {
    this.addedFiles = [];
    this.fileSelected = false;
  }


  override ngSubmit(): void {
    this.group.markAllAsTouched();
    const start = this.toDate(this.group.get('startDate').value);
    const end = this.toDate(this.group.get('endDate').value);
    if (start > end) {
      this.toastr.error('La fecha de inicio no puede ser mayor a la fecha de finalización.');
      return;
    }
    if (this.group.valid) {
      const body = this.group.getRawValue();
      body.startDate = start;
      body.endDate = end;
      body.uploadByUserId = this.authenticationService.authService.model.id;
      const id = get(body, 'id', null);
      let subscribe: Observable<any>;
      let path = 'banners';
      if (id !== null) {
        path += `/${id}`;
        subscribe = this.api.put<Banner>(path, body);
      } else {
        subscribe = this.api.post<Banner>(path, body);
      }
      subscribe.subscribe({
        complete: () => (this.submit = false),
        error: err => {
          this.toastr.error(
            err?.error?.message || err?.message || 'Ocurrió un error.'
          );
        },
        next: response2 => {
          this.toastr.success('Cambios aplicados.');
          this.subject$.next(response2);
          this.submitEvent.emit(response2);
          if (this.isCreateSubject$.value) {
            this.group.reset();
          }
          this.router.navigate([`/admin/marketing/banners`]);
        },
      });
    }
  }


  lightboxImage(url: string, caption = '') {
    const src = url;
    const thumb = 'tumb';
    const album =
      caption === ''
        ? { src: src, thumb: thumb }
        : { src: src, caption: caption, thumb: thumb };
    const _albums = [];
    _albums.push(album);
    this.lightbox.open(_albums, 0);
  }


  dettachMedia() {
    this.group.patchValue({
      mediaId: null
    });
    this.media = null;
  }

  dettachMediaSm() {
    this.group.patchValue({
      mediaSmId: null
    });
    this.mediaSm = null;
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

