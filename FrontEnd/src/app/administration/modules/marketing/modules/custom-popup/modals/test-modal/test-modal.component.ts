import { Component, Input } from '@angular/core';
import { ProductPromotion } from '@models/promotion/promotion-pop-up.model';

@Component({
  selector: 'app-test-modal',
  templateUrl: './test-modal.component.html',
  styleUrls: ['./test-modal.component.scss']
})
export class TestModalComponent {
  @Input() productPromotion: ProductPromotion;
  productPromotionData: ProductPromotion;

  constructor() { }


}
