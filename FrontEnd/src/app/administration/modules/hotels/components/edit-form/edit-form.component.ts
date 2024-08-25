import { Component, Inject, OnInit } from '@angular/core';
import { AuthenticationService } from '@services/account/authentication.service';
import { UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonFormComponent } from '@components/abstract/common-form.component';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { ModelService } from '@services/common/model.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { get } from 'lodash';
import { Media } from '@models/media/media.model';
import { NgxDropzoneChangeEvent } from 'ngx-dropzone';
import { readFile } from '@functions/files';
import { Lightbox } from 'ngx-lightbox';
import { Hotel } from '@models/hotels/hotel.model';

@Component({
  selector: 'app-edit-form',
  templateUrl: './edit-form.component.html',
  styleUrls: ['./edit-form.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: 'hotels' },
    CommonApiService,
    CommonVerbsApiService,
  ],
})
export class EditFormComponent
  extends CommonFormComponent<Hotel, Hotel>
  implements OnInit {
  editing = false;
  hotelId: string | null = null;
  media: Media[] = [];


  updated = false;
  initDataLoaded = false;
  TRANSLATE_KEY = 'MODEL_BOTS.MODULES.CRON-JOBS.COMPONENTS.EDIT-FORM.'

  constructor(
    private route: ActivatedRoute,
    @Inject('HotelService')
    private hotelService: ModelService<Hotel>,
    builder: UntypedFormBuilder,
    api: CommonApiService,
    toastr: ToastrService,
    private api2: CommonVerbsApiService,
    private router: Router,
    private http: HttpClient,
    public authenticationService: AuthenticationService,
    private lightbox: Lightbox,
  ) {
    super(builder, api, toastr);
    this.group = this.builder.group({
      id: [null],
      name: ['', Validators.required],
      description: ['', [Validators.required]],
      country: [null, [Validators.required]],
      city: [null],
      address: [null],
      media: [[], []],
    });
  }

  ngOnInit(): void {
    const subscribe = this.hotelService.model$.subscribe(hotel => {
      if (hotel) {
        this.init(hotel);
      }
      this.initDataLoaded = true;
    });
    this.unsubscribe.push(subscribe);

    const subscribeForm = this.submitEvent.subscribe(model => {
      if (model) {
        this.hotelService.set(model);
      }
    });
    this.unsubscribe.push(subscribeForm);
  }


  get f() {
    return this.group.controls;
  }


  private init(hotel: Hotel) {
    this.editing = true;
    this.hotelId = hotel.id;
    this.media = hotel.media;
    this.group.patchValue({
      id: hotel.id,
      name: hotel.name,
      description: hotel.description,
      country: hotel.country,
      city: hotel.city,
      address: hotel.address,
      media: hotel.media?.map(e => e.id),
    });
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
                media: [...this.group.get('media').value, r[0].id]
              });
              this.media.unshift(r[0]);
            },
            error => {
              this.toastr.error(error?.error?.message || 'Ocurrió un error.');
            }
          );
        this.unsubscribe.push(subscribe);
      });
    }
  }


  dettachMedia(media: Media, index: number) {
    const mediaForm = this.group.get('media').value;
    const formIndex = mediaForm.findIndex(e => e.id === media.id);
    mediaForm.splice(formIndex, 1);
    this.group.patchValue({
      media: mediaForm
    });
    this.media.splice(Number(index), 1);
  }


  override ngSubmit(): void {
    this.submit = true;
    if (this.group.valid) {
      const body = this.group.getRawValue();
      body.uploadByUserId = this.authenticationService.authService.model.id;
      const id = get(body, 'id', null);
      let subscribe: Observable<any>;
      let path = '/';
      if (id !== null) {
        path += `${id}`;
        subscribe = this.api.put<Hotel>(path, body);
      } else {
        subscribe = this.api.post<Hotel>(path, body);
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
          this.subject$.next(response2);
          this.submitEvent.emit(response2);
          if (this.isCreateSubject$.value) {
            this.group.reset();
          }
          this.router.navigate([`admin/hotels`]);
        },
      });
    }
  }
}
