import { Component, Inject, OnInit } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, UntypedFormBuilder, UntypedFormGroup } from '@angular/forms';
import { Validators } from '@angular/forms';
import { AuthenticationService } from '@services/account/authentication.service';
import { NgxDropzoneChangeEvent } from 'ngx-dropzone';
import { CommonVerbsApiService } from 'src/app/core/services/common/common-verbs-api.service';
import { Router, UrlHandlingStrategy } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { ActivatedRoute } from '@angular/router';
import { CommonFormComponent } from '@components/abstract/common-form.component';
import { ProductPromotion } from '@models/promotion/promotion-pop-up.model';
import { CommonApiService } from '@services/common/common-api.service';
import { ModelService } from '@services/common/model.service';
import { start } from '@popperjs/core';
import { get } from 'lodash';
import { Observable } from 'rxjs';
import { NgbDatepickerConfig, NgbDateStruct, NgbInputDatepickerConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { TestModalComponent } from '../../modals/test-modal/test-modal.component';
import { Media } from '@models/media/media.model';
import { readFile } from '@functions/files';
import { Lightbox } from 'ngx-lightbox';
@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: '' },
    CommonApiService,
    CommonVerbsApiService,
  ],
})
export class FormComponent
extends CommonFormComponent<ProductPromotion,ProductPromotion>
 implements OnInit{

  file:any;
  addedFiles: any[] = [];
  fileSelected = false;
  initDataLoaded = false;
  media: Media = null;
  submit =true;

  constructor(
    private route: ActivatedRoute,
    @Inject('PromotionService')
    private promotionService: ModelService<ProductPromotion>,
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
  ){
    super(builder,api,toastr)
    
    this.group = this.builder.group({
      id: [null],
      title: [null, [Validators.required]],
        startDate: [null, [Validators.required,]],
      endDate: [null, [Validators.required,]],
      description: [null, [Validators.required]],
      link: [null, [Validators.required]],
      discountPercentage: [null, [Validators.required]],
      media: [null, [Validators.required]],
    });
  }


  ngOnInit(): void {
    const subscribe = this.promotionService.model$.subscribe(product => {
      if (product) {
        this.init(product);
      }
      this.initDataLoaded = true;
    });
    this.unsubscribe.push(subscribe);

    const subscribeForm = this.submitEvent.subscribe(model => {
      if (model) {
        this.promotionService.set(model);
      }
    });
    this.unsubscribe.push(subscribeForm);
  }




  get f() {
    return this.group.controls;
  }

  private init(ProductPromotion: ProductPromotion) {
   const startDate= this.fromDateISOString(ProductPromotion.startDate);
   const endDate= this.fromDateISOString(ProductPromotion.endDate);
   this.group.patchValue({
      id: ProductPromotion.id,
      title: ProductPromotion.title,
      startDate: startDate,
      endDate: endDate,
      description: ProductPromotion.description,
      link: ProductPromotion.link,
      discountPercentage: ProductPromotion.discountPercentage,
      media: ProductPromotion.media,
    });
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
      body.startDate =  start;
      body.endDate =end;
      body.uploadByUserId = this.authenticationService.authService.model.id;
      const id = get(body, 'id', null);
      let subscribe: Observable<any>;
      let path = 'promotions';
      if (id !== null) {
        path += `${id}`;
        subscribe = this.api.form<ProductPromotion>(path, body);
      } else {
        subscribe = this.api.form<ProductPromotion>(path, body);
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
          this.router.navigate([`/admin/marketing/custom-popup`]);
        },
      });
    }
  }

  openModal() {
    if(this.group.invalid){
      this.group.markAllAsTouched();
      this.toastr.error('Por favor complete los campos requeridos.');
      return;
    }

    const modalRef = this.modalService.open(TestModalComponent, { size: 'lg' });
    modalRef.componentInstance.productPromotion = this.group.value;
  }

  onSelect(event: NgxDropzoneChangeEvent) {
    const file = event.addedFiles[0];
    if (file) {
      readFile(file).then(() => {
        const subscribe = this.api
          .form<Media[]>(`upload`, {
            file: file,
            source: 'PRODUCT',
            bytesSize: file.size
          })
          .subscribe(
            r => {
              this.group.patchValue({
                media: r[0].url
              });
              this.media= r[0];
            },
            error => {
              this.toastr.error(error?.error?.message || 'Ocurrió un error.');
            }
          );
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
      media: null
    });
    this.media= null;
  }

    // Método para convertir NgbDateStruct a Date
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
        return null; // La cadena no tiene el formato esperado YYYY-MM-DD
      }
    
      const year = parseInt(dateParts[0], 10);
      const month = parseInt(dateParts[1], 10);
      const day = parseInt(dateParts[2], 10);
    
      // Verificar si los componentes de fecha son números válidos
      if (isNaN(year) || isNaN(month) || isNaN(day)) {
        return null;
      }
    
      // Crear un NgbDateStruct a partir de los componentes de fecha
      const ngbDate: NgbDateStruct = { year, month, day };
    
      return ngbDate;
    }
  
  
 }
  
