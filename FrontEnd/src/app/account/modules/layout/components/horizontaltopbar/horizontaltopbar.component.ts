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
import { ActivatedRoute, Router } from '@angular/router';
import { ThemeService } from '@services/layout/theme-service.service';
import { CountCartService } from '@services/layout/count-cart.service';

@Component({
  selector: 'app-horizontaltopbar',
  templateUrl: './horizontaltopbar.component.html',
  styleUrls: ['./horizontaltopbar.component.scss'],
})
export class HorizontaltopbarComponent
  extends BaseTopBarComponent
  implements OnInit
{
  menuItems: MenuItem[] = [];
  attribute: string;
  mode: string;
  isLoggedIn = false;
  countCart: number = 0;
  TRANSLATE_KEY = 'ACCOUNT.MODULES.LAYOUT.COMPONENTS.HORIZONTALTOPBAR.';

  constructor(
    @Inject(DOCUMENT) document: InjectionToken<Document>,
    router: Router,
    languageService: LanguageService,
    cookiesService: CookieService,
    authenticationService: AuthenticationService,
    private eventService: EventService,
    private route: ActivatedRoute,
    private car: CountCartService,
    private themeService: ThemeService
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

    this.attribute = this.themeService.getLayout();
    const modeAttribute = this.themeService.getTheme();
    this.mode= modeAttribute !== '' ? modeAttribute : 'light';
    this.getCart();
  }

  changeLayout() {
    this.themeService.setLayout('vertical');
    this.eventService.broadcast('changeLayout', 'vertical');
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
