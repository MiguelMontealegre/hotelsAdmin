import { AuthenticationService } from '@services/account/authentication.service';
import { BaseTopBarComponent } from '@components/abstract/base-top-bar.component';
import { Component, OnInit, Inject, InjectionToken } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { DOCUMENT } from '@angular/common';
import { EventService } from '@services/layout/event.service';
import { LanguageService } from '@services/layout/language.service';
import { MenuItem } from '@models/layout/menu.model';
import { Router } from '@angular/router';
import { dataModules } from '@database/hotels-modules';
import { ThemeService } from '@services/layout/theme-service.service';
import { of, switchMap } from 'rxjs';
import { CountCartService } from '@services/layout/count-cart.service';

@Component({
  selector: 'app-horizontaltopbar',
  templateUrl: './horizontaltopbar.component.html',
  styleUrls: ['./horizontaltopbar.component.scss'],
})
export class HorizontaltopbarComponent extends BaseTopBarComponent implements OnInit {
  menuItems: MenuItem[] = dataModules;
  attribute: string;
  mode: string;
  countCart: number = 0;
  isLoggedIn = false;
  TRANSLATE_KEY = 'ADMIN.LAYOUT.COMPONENTS.HORIZONTALTOPBAR.';
  roleNames=[];
  constructor(
    @Inject(DOCUMENT) document: InjectionToken<Document>,
    router: Router,
    languageService: LanguageService,
    cookiesService: CookieService,
    authenticationService: AuthenticationService,
    private countCartService: CountCartService, // Renombrado para claridad
    private themeService: ThemeService,
    private eventService: EventService
  ) {
    super(document, router, languageService, cookiesService, authenticationService);
  }

  override ngOnInit(): void {
    super.ngOnInit();
    this.getCartCount();
    this.checkRole();

    const attributeAux = this.themeService.getLayout();
    this.attribute = attributeAux !== '' ? attributeAux : 'horizontal';

    const modeAttribute = this.themeService.getTheme();
    this.mode = modeAttribute !== '' ? modeAttribute : 'light';

    this.authenticationService.isLoggedIn().then(isLogged => {
      this.isLoggedIn = isLogged;
      if (this.isLoggedIn) {
        const subscribe = of(this.authenticationService.authService.model)
          .pipe(
            switchMap(data => data ? of(data) : this.authenticationService.getAccount())
          )
          .subscribe(response => {
            if (response) {
              this.authenticationService.authService.set(response);
            } else {
              this.authenticationService.logout();
            }
          });
        this.unsubscribe.push(subscribe);
      }
    });
  }
  checkRole() {
    if (this.authenticationService?.authService?.model) {
      const user = this.authenticationService?.authService?.model;
      const roleNames = user.roles.map(r => r.name);
      this.roleNames = roleNames as string[];
    }
  }

  changeLayout() {
    this.themeService.setLayout('vertical');
    this.eventService.broadcast('changeLayout', 'vertical');
  }

  changeMode(themeMode: string) {
    this.mode = themeMode;
    this.themeService.setTheme(themeMode);
  }

  getCartCount() {
    const cartCountSubscription = this.countCartService.getCartCountUpdates().subscribe(
      (count: number) => {
        this.countCart = count;
      },
      (error) => {
        console.error('Error al obtener la cantidad de productos del carrito:', error);
      }
    );
    this.unsubscribe.push(cartCountSubscription);
    this.countCartService.updateCartCount();
  }
}
