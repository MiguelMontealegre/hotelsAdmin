import { CommonApiService } from '@services/common/common-api.service';
import { CommonComponent } from '@components/abstract/common-component.component';
import { Component } from '@angular/core';
import { Inject } from '@angular/core';
import { ModelService } from '@services/common/model.service';
import { OnInit } from '@angular/core';
import { Lightbox } from 'ngx-lightbox';
import { AuthenticationService } from '@services/account/authentication.service';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { Hotel } from '@models/hotels/hotel.model';

@Component({
  selector: 'app-hotel-detail',
  templateUrl: './hotel-detail.component.html',
  styleUrls: ['./hotel-detail.component.scss'],
})
export class HotelDetailComponent extends CommonComponent implements OnInit {
  hotelDetail: Hotel = <Hotel>{};
  roleNames: string[] = [];


  constructor(
    private router: Router,
    @Inject('HotelService')
    public hotelService: ModelService<Hotel>,
    public api: CommonApiService,
    private lightbox: Lightbox,
    public authenticationService: AuthenticationService,
    private toastr: ToastrService,
    private api2: CommonVerbsApiService
  ) {
    super();
  }

  ngOnInit(): void {
    const subscribe = this.hotelService.model$.subscribe(value => {
      if (value != null) {
        this.load(value);
      }
    });
    this.unsubscribe.push(subscribe);
  }

  private load(model: Hotel) {
    this.hotelDetail = model;
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


}
