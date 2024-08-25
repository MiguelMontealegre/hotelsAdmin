import { Inject, Injectable } from '@angular/core';
import { Observable, of, Subject } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { AuthenticationService } from '../../../core/services/account/authentication.service';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class CountCartService {
  private cartCountSubject = new Subject<number>();

  constructor(
    private http: HttpClient,
    @Inject('API_URL') private api: string,
    public authenticationService: AuthenticationService
  ) { }

  getCount(): Observable<number> {
    const user = this.authenticationService.authService.model;

    if (user == null) {
      const cart = localStorage.getItem('cart');
      if (cart) {
        const cartItems = JSON.parse(cart);
        const totalQuantity = cartItems.reduce((sum: number, item: any) => sum + (item.quantity || 0), 0);

        this.cartCountSubject.next(totalQuantity);
        return of(totalQuantity);  
      } else {
        this.cartCountSubject.next(0);
        return of(0);  
      }
    } else {
      const userId = user.id;
      return this.http.get<any>(`${this.api}/cart-products/get-cart?userId=${userId}`).pipe(
        map((response: any) => {
          const totalQuantity = response.data.totalQuantity || 0;
          
          this.cartCountSubject.next(totalQuantity);
          return totalQuantity;
        }),
        catchError((error) => {
          console.error('Error al obtener la cantidad de productos del carrito:', error);
          
          this.cartCountSubject.next(0);
          return of(0);  
        })
      );
    }
  }

  getCartCountUpdates(): Observable<number> {
    return this.cartCountSubject.asObservable();
  }

  updateCartCount() {
    this.getCount().subscribe();
  }
}
