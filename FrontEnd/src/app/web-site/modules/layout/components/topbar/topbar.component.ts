import { AuthenticationService } from '@services/account/authentication.service';
import { BaseTopBarComponent } from '@components/abstract/base-top-bar.component';
import { Component } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { DOCUMENT } from '@angular/common';
import { EventService } from '@services/layout/event.service';
import { Inject } from '@angular/core';
import { InjectionToken } from '@angular/core';
import { LanguageService } from '@services/layout/language.service';
import { MenuItem } from '@models/layout/menu.model';
import { OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { dataModules } from '@database/subscription-panel-modules';
import { ThemeService } from '@services/layout/theme-service.service';
import { of, switchMap } from 'rxjs';
import { CountCartService } from '@services/layout/count-cart.service';

@Component({
  selector: 'app-topbar',
  templateUrl: './topbar.component.html',
  styleUrls: ['./topbar.component.scss'],
})
export class TopbarComponent
  extends BaseTopBarComponent
  implements OnInit
{
  menuItems: MenuItem[] = dataModules;
  attribute: string;
  mode: string;
  isLoggedIn = false;
  TRANSLATE_KEY = 'ADMIN.LAYOUT.COMPONENTS.HORIZONTALTOPBAR.';
  iconLight = false;
  roleNames: string[] = [];

  countCart: number = 0;
  constructor(
    @Inject(DOCUMENT) document: InjectionToken<Document>,
    router: Router,
    languageService: LanguageService,
    private car: CountCartService,
    cookiesService: CookieService,
    authenticationService: AuthenticationService,
    private themeService: ThemeService,
    private eventService: EventService
  ) {
    super(
      document,
      router,
      languageService,
      cookiesService,
      authenticationService
    );
  }

  override ngOnInit(): void {
    super.ngOnInit();
    this.checkRole();
    const attributeAux =this.themeService.getLayout();
    this.attribute = attributeAux !== '' ? attributeAux : 'horizontal';
    const modeAttribute = this.themeService.getTheme();
    this.mode= modeAttribute !== '' ? modeAttribute : 'light';
    const sidebarData = document.body.getAttribute('data-sidebar');
    if(sidebarData === 'dark'){
      this.iconLight = true;
    }
    this.authenticationService.isLoggedIn().then(next => {
      this.isLoggedIn = next;
      if (this.isLoggedIn) {
        const subscribe = of(this.authenticationService.authService.model)
          .pipe(
            switchMap(data =>
              data ? of(data) : this.authenticationService.getAccount()
            )
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
    this.getCart();
  }

  changeLayout() {
    this.themeService.setLayout('vertical');
    this.eventService.broadcast('changeLayout', 'vertical');
  }
  checkRole() {
    if (this.authenticationService?.authService?.model) {
      const user = this.authenticationService?.authService?.model;
      const roleNames = user.roles.map(r => r.name);
      this.roleNames = roleNames as string[];
    }
  }
  windowScroll() {
    const navbar = document.getElementById('navbar');
    if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {
      navbar.classList.add('nav-sticky')
    }
  }
  toggleMenu() {
    document.getElementById('topnav-menu-content').classList.toggle('show');
  }
  changeMode(themeMode: string) {
    this.mode = themeMode;
    this.themeService.setTheme(themeMode);
  }
  getCart(){
    this.car.getCount().subscribe(
      (count: number) => {
        this.countCart = count;
      },
      (error) => {
        console.error('Error al obtener la cantidad de productos del carrito:', error);
      }
    );
  }

}
