import { Component } from '@angular/core';
import { Category } from '@models/categories/category.model';
import { AuthenticationService } from '@services/account/authentication.service';
import { CommonApiService } from '@services/common/common-api.service';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.scss'],
})
export class FooterComponent {
  year: number = new Date().getFullYear();
  isLoggedIn = false;
  animalsCategories: Category[] = []; 
  constructor(
    public authenticationService: AuthenticationService,
    private api: CommonApiService
  ){

  }

  ngOnInit() {
    this.authenticationService.isLoggedIn().then(next => {
      this.isLoggedIn = next;
    });
    this.api.get<any>('categories', { page: 1 }).subscribe((data) => {
      this.animalsCategories = data.data;
    });
  }
}
