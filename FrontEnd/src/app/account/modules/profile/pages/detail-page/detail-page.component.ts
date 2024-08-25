import { Component, OnInit } from '@angular/core';

import { ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '@services/account/authentication.service';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonFormComponent } from '@components/abstract/common-form.component';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { MenuItem } from '@models/layout/menu.model';
import { Observable } from 'rxjs';
import { ToastrService } from 'ngx-toastr';
import { UntypedFormBuilder } from '@angular/forms';
import { User } from '@models/account/user.model';
import { Validators } from '@angular/forms';
import { get } from 'lodash';

@Component({
  selector: 'app-detail-page',
  templateUrl: './detail-page.component.html',
  styles: [],
})
export class DetailPageComponent

  extends CommonFormComponent<User, User>
  implements OnInit
{
  user: User = this.route.snapshot.data.user;
  menuAccountItems: Array<{}> = [
    { label: 'Perfil', active: true },
    { label: 'Editar confifuracion de la cuenta', active: true,},
  ];
  isSubscribed = false;
  TRANSLATE_KEY = 'ACCOUNT.MODULES.PROFILE.PAGES.DETAIL.';
  constructor(
    builder: UntypedFormBuilder,
    api: CommonVerbsApiService,
    toastr: ToastrService,
    private route: ActivatedRoute,
    public authenticationService: AuthenticationService
  ) {
    super(builder, api, toastr, `users`);

    this.group = this.builder.group({
      id: [this.user.id, [Validators.required]],
      email: [
        this.user.email,
        [Validators.required, Validators.email],
      ],
      firstName: [
        this.user.firstName,
        [Validators.required, Validators.minLength(3)],
      ],
      lastName: [
        this.user.lastName,
        [Validators.required, Validators.minLength(3)],
      ],
      preferredName: [this.user.profile?.preferredName],
      about: [this.user.profile?.about],
      media: [this.user.profile?.media]
    });
  }

  ngOnInit(): void {
    this.group.get('media')?.valueChanges.subscribe(media => {
      if (media) {
        const subscribe1 = this.api
          .post(`users/update-profile-media`, {
            userId: this.user.id,
            mediaId: media.id,
          })
          .subscribe({
            next: () => {
              this.authenticationService.authService.model.profile.media = media;
              this.authenticationService.authService.set(this.authenticationService.authService.model);
            },
          });
        this.unsubscribe.push(subscribe1);
      }
    });
    this.isCreateSubject$.next(false);
  }


  override ngSubmit(): void {
    this.submit = true;
    if (this.group.valid) {
      const body = this.group.getRawValue();
      const id = get(body, 'id', null);
      let subscribe: Observable<any>;
      let path = 'users/';
      if (id !== null) {
        path += `${id}`;
        subscribe = this.api.put<User>(path, body);
      } else {
        subscribe = this.api.post<User>(path, body);
      }
      subscribe.subscribe({
        complete: () => (this.submit = false),
        error: err => {
          this.toastr.error(
            err?.error?.message || err?.message || 'An ocurrido un Error.'
          );
        },
        next: response2 => {
          this.toastr.success('Cambios Aplicados');
          this.subject$.next(response2);
          this.submitEvent.emit(response2);
          this.authenticationService.authService.set(response2);
          if (this.isCreateSubject$.value) {
            this.group.reset();
          }
        },
      });
    }
  }
}
