import * as ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonComponent } from '@components/abstract/common-component.component';
import { Component } from '@angular/core';
import { Inject } from '@angular/core';
import { ModelService } from '@services/common/model.service';
import { OnInit } from '@angular/core';
import { Product } from '@models/products/product.model';
import { Feature } from '@models/products/product-feature.model';
import { Lightbox } from 'ngx-lightbox';
import { AuthenticationService } from '@services/account/authentication.service';
import { Router } from '@angular/router';
import { ProductComment } from '@models/products/product-comment.model';
import { PaginatedCollection } from '@models/collection/paginated-collection';
import { ToastrService } from 'ngx-toastr';
import { includes, map, some } from 'lodash';
import { alertFire } from '@functions/alerts';
import { CartProduct } from '@models/cart/cart-product.model';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { Size } from '@models/products/product-size.model';
import { Color } from '@models/products/product-color.model';
import { CountCartService } from '@services/layout/count-cart.service';

@Component({
  selector: 'app-product-detail',
  templateUrl: './product-detail.component.html',
  styleUrls: ['./product-detail.component.scss'],
})
export class ProductDetailComponent extends CommonComponent implements OnInit {
  productDetail: Product = <Product>{};

  halfIndex: number;
  firstHalf: Array<Feature> = [];
  secondHalf: Array<Feature> = [];

  selectedSize: Size;
  selectedColor: Color;
  showColorsMedia = false;

  liked = false;

  public Editor = ClassicEditor;
  commentContent = '';
  activeReply = false;
  replyCommentData: ProductComment;


  quantity = 1;
  roleNames: string[] = [];


  constructor(
    private router: Router,
    @Inject('ProductService')
    public productService: ModelService<Product>,
    public api: CommonApiService,
    private lightbox: Lightbox,
    public authenticationService: AuthenticationService,
    private countCartService: CountCartService,
    private toastr: ToastrService,
    private api2: CommonVerbsApiService
  ) {
    super();
  }

  ngOnInit(): void {
    const subscribe = this.productService.model$.subscribe(value => {
      if (value != null) {
        this.load(value);
      }
    });
    this.unsubscribe.push(subscribe);
  }

  private load(model: Product) {
    this.productDetail = model;
    this.showColorsMedia = false;
    this.selectedColor = null;
    if (this.authenticationService?.authService?.model) {
      this.checkLike();
      this.checkRole();
    }
    this.getComments();
    if (this.productDetail.features.length > 0) {
      this.halfIndex = Math.ceil(this.productDetail.features.length / 2);
      this.firstHalf = this.productDetail.features.slice(0, this.halfIndex);
      this.secondHalf = this.productDetail.features.slice(this.halfIndex);
    }
    if(this.productDetail?.colors?.length > 0){
      this.selectedColor = this.productDetail.colors[0];
      this.showColorsMedia = true;
    }
  }


  changeSelectedColor(color: Color){
    setTimeout(() => {
      this.selectedColor = color;
      this.showColorsMedia = true;
    }, 200);
  }


  showProductMedia(){
    setTimeout(() => {
      this.showColorsMedia = false;
    }, 200)
  }


  checkRole(){
    const user = this.authenticationService?.authService?.model;
    const roleNames = map(user.roles, r => r.name);
    this.roleNames = roleNames;
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


  checkLike() {
    this.api.post<{ status: string }>(`/check-like/${this.productDetail.id}`)
      .subscribe(r => {
        if (r.status) {
          this.liked = r.status === 'attached' ? true : false;
        }
      });
  }


  like() {
    if (this.authenticationService.authService.model) {
      this.api.post<{ status: string }>(`/like/${this.productDetail.id}`)
        .subscribe(r => {
          if (r.status) {
            this.liked = r.status === 'attached' ? true : false;
          }
        });
    } else {
      this.router
        .navigate([`/account/auth/login-2`])
        .then();
    }
  }


  comments: ProductComment[];
  commentsPage = 1;
  totalComments: number;
  getComments() {
    this.api
      .get<PaginatedCollection<ProductComment>>(
        '/product-comments',
        {
          page: this.commentsPage,
          limit: 10,
          orderBy: 'createdAt',
          direction: 'DESC',
        },
        [],
        [
          {
            key: 'products',
            values: [this.productDetail.id]
          },
        ]
      )
      .subscribe(r => {
        this.totalComments = r.meta.total;
        if (this.commentsPage > 1) {
          this.comments = this.comments.concat(r.data)
        } else {
          this.comments = r.data;
        }
      });
  }


  showMoreComments() {
    this.commentsPage++;
    this.getComments();
  }


  saveComment() {
    if (this.commentContent.length === 0) {
      this.toastr.error('El contenido debe ser válido');
      return;
    }
    if (this.authenticationService.authService.model) {
      const commentId = (this.activeReply && this.replyCommentData) ? (this.replyCommentData?.parentComment ? this.replyCommentData?.parentComment.id : this.replyCommentData.id) : null;
      const replyId = this.activeReply && this.replyCommentData && this.replyCommentData.parentComment ? this.replyCommentData.id : null;
      const params = {
        content: this.commentContent,
        commentId: commentId,
        replyId: replyId,
      };
      this.api.post<ProductComment>(`/comment/${this.productDetail.id}`, params)
        .subscribe(r => {
          if (r) {
            if (r.parentComment) {
              const parentCommentIndex = this.comments.findIndex(e => e.id === r.parentComment.id);
              this.comments[parentCommentIndex].replies.push(r);
              this.commentContent = '';
              this.cancelReply();
            } else {
              this.comments.unshift(r);
              this.commentContent = '';
              this.totalComments = this.totalComments + 1;
            }
          }
        });
    } else {
      this.router
        .navigate([`/account/auth/login-2`])
        .then();
    }
  }


  replyComment(comment: ProductComment) {
    this.activeReply = true;
    this.replyCommentData = comment;
  }


  cancelReply() {
    this.activeReply = false;
    this.replyCommentData = null;
  }


  incrementQuantity() {
    if (this.quantity === this.productDetail.availableQuantity) {
      return;
    } else if (this.quantity > this.productDetail.availableQuantity) {
      this.quantity = this.productDetail.availableQuantity;
    } else {
      this.quantity++;
    }
  }

  decrementQuantity() {
    if (this.quantity === 1) {
      return;
    } else if (this.quantity < 1) {
      this.quantity = 1;
    } else {
      this.quantity--;
    }
  }


  addToCart() {
    alertFire('').then(result => {
      if (result.value) {
        if (this.productDetail?.sizes?.length > 0) {
          if (!this.selectedSize) {
            this.toastr.error('Por Favor Seleciona un Tamaaño Valido.');
            return;
          }
        }
        if (this.productDetail?.colors?.length > 0) {
          if (!this.selectedColor) {
            this.toastr.error('Por favor Seleciona un color Valido.');
            return;
          }
        }
        if (this.authenticationService.authService.model) {
          const productData = {
            productId: this.productDetail.id,
            userId: this.authenticationService.authService.model.id,
            quantity: this.quantity,
            sizeId: this.selectedSize?.id ?? null,
            colorId: this.selectedColor?.id ?? null,
          };
          this.api2.post(`cart-products/add-product`, productData)
            .subscribe(r => {
              if (r) {
                this.countCartService.updateCartCount();
                this.toastr.success('Producto añadido exitosamente.')
              }
            },
              error => {
                this.toastr.error('La cantidad es Invalidad!' || error || error?.error || error?.error?.message);
              });
        } else {
          const productData = {
            product: this.productDetail,
            userId: null,
            quantity: this.quantity,
            size: this.selectedSize ?? null,
            color: this.selectedColor ?? null,
          };

          const cart = this.getCartFromLocalStorage();
          const cartProducts = cart.filter(p => p.product.id === this.productDetail.id);
          const cartProductTotalQuantity = cartProducts.reduce((total, product) => total + product.quantity, 0);
          let productMatch = false;

          if (this.productDetail.availableQuantity < this.quantity + cartProductTotalQuantity) {
            this.toastr.error('La cantidad es invalida!');
            return;
          }
          if (cart.length > 0) {
            for (const cartProduct of cartProducts) {
              if (cartProduct && cartProduct.size?.id === this.selectedSize?.id && cartProduct.color?.id === this.selectedColor?.id) {
                cartProduct.quantity = cartProduct.quantity + this.quantity;
                localStorage.setItem('cart', JSON.stringify(cart));
                productMatch = true;
                this.countCartService.updateCartCount();
                this.toastr.success('Producto añadido exitosamente.')
              }
            }
            if (!productMatch) {
              cart.unshift(productData);
              localStorage.setItem('cart', JSON.stringify(cart));
              this.countCartService.updateCartCount();
              this.toastr.success('Producto añadido exitosamente.')
            }
          } else {
            cart.unshift(productData);
            localStorage.setItem('cart', JSON.stringify(cart));
            this.countCartService.updateCartCount();
            this.toastr.success('Producto añadido exitosamente.')
          }
        }
      }
    });
  }



  getCartFromLocalStorage(): CartProduct[] {
    const cartItemsJSON = localStorage.getItem('cart');
    if (cartItemsJSON) {
      return JSON.parse(cartItemsJSON);
    } else {
      return [];
    }
  }


}
