import { Component, OnInit } from '@angular/core';
import { DomSanitizer, Meta, Title } from '@angular/platform-browser';
import { UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';
import { AuthenticationService as authService }  from '@services/account/authentication.service';
import { AuthenticationService } from '../../../core/services/account/authentication.service';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonComponent } from '@components/abstract/common-component.component';
import { CommonLoginComponent } from '@modules/authentication/common-login/common-login.component';
import { CookieService } from 'ngx-cookie-service';
import { CountCartService } from '@services/layout/count-cart.service';
import { EventService } from '@services/layout/event.service';
import { LanguageService } from '@services/layout/language.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { ProductPromotion } from '@models/promotion/promotion-pop-up.model';
import { Router } from '@angular/router';
import { ThemeService } from '@services/layout/theme-service.service';
import { ToastrService } from 'ngx-toastr';
import { User } from '@models/account/user.model';
import { environment } from '@environments/environment';
import { interval } from 'rxjs';
import { map } from 'rxjs/operators';
import { of } from 'rxjs';
import { switchMap } from 'rxjs';
import { Category } from '@models/categories/category.model';

@Component({
  selector: 'app-contact-us',
  templateUrl: './contact-us.component.html',
  styleUrls: ['./contact-us.component.scss']
})
export class ContactUsComponent extends CommonComponent implements OnInit {
  externalAuthUrl = environment.api + '/external-auth/redirect';
  isLoggedIn = false;
  user: User;
  translatedText = '';
  // set the currenr year
  year: number = new Date().getFullYear();
  currentSection = 'home';
  cookieValue = '';

  carouselOption: OwlOptions = {
    items: 3,
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
        items: 2,
      },
      768: {
        items: 3,
      },
    },
  }

  timelineCarousel: OwlOptions = {
    items: 1,
    loop: false,
    margin: 0,
    nav: true,
    navText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"],
    dots: false,
    responsive: {
      672: {
        items: 3
      },

      576: {
        items: 2
      },

      936: {
        items: 4
      },
    }
  }

  promotions: ProductPromotion[] = [];

  private _trialEndsAt;

  private _diff: number;
  _days: number;
  _hours: number;
  _minutes: number;
  _seconds: number;
  attribute: string;
  mode: string;

  submit = false;
  group: UntypedFormGroup = this.formBuilder.group({
    name: [null, [Validators.required]],
    email: [null, [Validators.required, Validators.email]],
  });

  countCart = 0;
  apiLoaded = false;
  key = environment.maps;
  center: google.maps.LatLngLiteral = { lat: 4.602713189186805, lng: -74.08392418343502 };
  zoomLevel?: number = 10;
  markerOptions: google.maps.MarkerOptions = {
    draggable: false,
  };
  markers1 = [
    {
      lat: 4.60149330902331,
      lng: -74.08055181535654,
    }
  ]
  markers2 = [
    {
      lat: 4.602713189186805,
      lng: -74.08392418343502,
    }
  ]
  roleNames: string[];
  animalsCategories: Category[] = []; 

  constructor(
    private car: CountCartService,
    private router: Router,
    public authenticationService: AuthenticationService,
    private modalService: NgbModal,
    private eventService: EventService,
    private toastr: ToastrService,
    private themeService: ThemeService,
    public languageService: LanguageService,
    public authService :authService,
    public cookiesService: CookieService,
    public _cookiesService: CookieService,
    private sanitizer: DomSanitizer,
    private api: CommonApiService,
    private formBuilder: UntypedFormBuilder,) {
    super();
    this.cookieValue = this.cookiesService.get('lang');
    const modeAttribute = this.themeService.getTheme();
    this.mode = modeAttribute !== '' ? modeAttribute : 'light';
  }

  ngOnInit() {
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
              this.getCart();
              this.authenticationService.authService.set(response);

            } else {
              this.authenticationService.logout();
            }
          });
        this.unsubscribe.push(subscribe);
      } else {
        this.car.getCount();
      }
    });
    this.checkRole();
    this.api.get<any>('categories', { page: 1 }).subscribe((data) => {
      this.animalsCategories = data.data;
    });
  }

  changeMode(themeMode: string) {
    this.mode = themeMode;
    this.themeService.setTheme(themeMode);
  }
  getCart() {
    this.car.getCount().subscribe(
      (count: number) => {
        this.countCart = count;
      },
      (error) => {
        console.error('Error al obtener la cantidad de productos del carrito:', error);
      }
    );
  }
  checkRole() {
    if (this.authService?.authService?.model) {
      const user = this.authService?.authService?.model;
      const roleNames = user.roles.map(r => r.name);
      console.log(roleNames);
      this.roleNames = roleNames as string[];
    }
  }

  get f() {
    return this.group.controls;
  }


  /**
   * Toggle navbar
   */
  toggleMenu() {
    document.getElementById('topnav-menu-content').classList.toggle('show');
  }

  /**
   * Section changed method
   * @param sectionId specify the current sectionID
   */
  onSectionChange(sectionId: string) {
    this.currentSection = sectionId;
  }

  onOpenLoginModal() {
    this.modalService.open(CommonLoginComponent, { centered: true });
  }

  redirectToExternalAuth(key: string): void {
    window.location.href = this.externalAuthUrl + `?role=SINGLE_USER&provider=${key}`;
  }

  zoomChanged(level: number | undefined) {
    this.zoomLevel = level;
  }
  goWhosale(): void {

    if (this.isLoggedIn) {
      this.toastr.error('No puedes Registrarte como Mayorista, primero debes Cerrar la Session Actual ')
    } else {
      this.router.navigate(['./wholesale-form'])
    }
  }
  windowScroll() {
    const navbar = document.getElementById('navbar');
    if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {
      navbar.classList.add('nav-sticky')
    }
  }



  ngSubmit(): void {
    this.submit = true;
    if (this.group.invalid) {
      return;
    }
    const group = this.group.value;
    const formData = {
      ...group,
    };
    console.log(formData);
    // this.api.form<any>('', formData)
    //   .subscribe(r => {
    //     if (r) {
    //       this.toastr.success('Solicitud enviada con éxito');
    //       this.router.navigate(['/']);
    //     } error => {
    //       this.toastr.error(error || 'Error');
    //     }
    //   }, error => {
    //     this.toastr.error(error || 'Error');
    //   });
  }
}
