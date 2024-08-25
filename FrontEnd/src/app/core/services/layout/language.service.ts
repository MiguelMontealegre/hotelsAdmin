import { CookieService } from 'ngx-cookie-service';
import { Injectable } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Injectable({ providedIn: 'root' })
export class LanguageService {
  public languages: string[] = [ 'es'];

  constructor(
    public translate: TranslateService,
    private cookieService: CookieService
  ) {
    let browserLang = 'es';
    this.translate.addLangs(this.languages);
    if (this.cookieService.check('lang')) {
      browserLang = 'es'
    } else {
      this.setLanguage('es');
      browserLang = translate.getBrowserLang() ?? '';
    }
    translate.use(browserLang.match(/|es/) ? browserLang : 'es');
  }

  public setLanguage(lang: string) {
    this.translate.use(lang);
    this.cookieService.set('lang', lang);
  }
}
