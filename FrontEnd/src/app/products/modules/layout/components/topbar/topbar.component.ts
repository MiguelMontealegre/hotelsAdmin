import { AuthenticationService } from '@services/account/authentication.service';
import { BaseTopBarComponent } from '@components/abstract/base-top-bar.component';
import { Component } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { DOCUMENT } from '@angular/common';
import { EventService } from '@services/layout/event.service';
import { Inject } from '@angular/core';
import { InjectionToken } from '@angular/core';
import { LanguageService } from '@services/layout/language.service';
import { OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ThemeService } from '@services/layout/theme-service.service';
import { of, switchMap } from 'rxjs';
@Component({
  selector: 'app-topbar',
  templateUrl: './topbar.component.html',
  styleUrls: ['./topbar.component.scss'],
  providers: [LanguageService],
})
export class TopbarComponent extends BaseTopBarComponent implements OnInit {
  attribute: string;
  mode: string;
  countCart: number = 0;
  TRANSLATE_KEY = 'ADMIN.LAYOUT.COMPONENTS.TOPBAR.';
  constructor(
    @Inject(DOCUMENT) document: InjectionToken<Document>,
    router: Router,
    languageService: LanguageService,
    cookiesService: CookieService,
    authenticationService: AuthenticationService,
    private eventService: EventService,
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
    const attributeAux = this.themeService.getLayout();;
    this.attribute = attributeAux !== '' ? attributeAux : 'horizontal';
    const modeAttribute = this.themeService.getTheme();
    this.mode= modeAttribute !== '' ? modeAttribute : 'light';
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
  }

  changeMode(themeMode: string) {
    this.mode = themeMode;
    this.themeService.setTheme(themeMode);
  }

  changeLayout() {
    this.themeService.setLayout('horizontal');
    this.eventService.broadcast('changeLayout', 'horizontal');
  }
}
