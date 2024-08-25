import { Component, Input } from '@angular/core';
import { ProductPromotion } from '@models/promotion/promotion-pop-up.model';
import { OwlOptions } from 'ngx-owl-carousel-o';

@Component({
  selector: 'app-promotions-modal',
  templateUrl: './promotions-modal.component.html',
  styleUrls: ['./promotions-modal.component.scss']
})
export class PromotionsModalComponent {
  @Input() productPromotions: ProductPromotion[];
  
  carouselOption: OwlOptions = {
    items: 1,
    loop: true,
    margin: 40,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    nav: false,
    dots: true,
    responsive: {
      0: {
        items: 1,
      },
      480: {
        items: 1,
      },
      768: {
        items: 1,
      },
    },
  };
  


  constructor() { }
}
