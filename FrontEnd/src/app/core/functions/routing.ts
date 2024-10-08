import { MenuItem } from '@models/layout/menu.model';
import { User } from '@models/account/user.model';
import { co } from '@fullcalendar/core/internal-common';
import { first } from 'lodash';
import { map } from 'lodash';

export function   getRouteByRole(user: User): string {
  const roleNames = map(user.roles, r => r.name);
  if (roleNames.includes('ADMIN')) {
    return '/admin/users';
  }
  if(
   roleNames.includes('WHOLESALE_USER')
  ){
    console.log('wholesale user',user);
    const role = user.roles.find(x => x.name === 'WHOLESALE_USER');
    if (role) {
      if (!user.emailConfirmedAt) {
        return '/account/auth/email-verification';
      } else {
        if (user.wholesaleUsers &&  user.wholesaleUsers.isApproved) {
          return '/products/portal';
        } else {
          return '/wholesale-pending';
        }
    }
  }
}
  if (
    roleNames.includes('SINGLE_USER')
  ) {
    const role = user.roles.find(x => x.name === 'SINGLE_USER');
    if (role) {
      return '/products/portal';
    }
  }
  return '/';
}

export function getMenuByRole(user: User): MenuItem[] {
  const menu: MenuItem[] = [];
  const roleNames = map(user.roles, r => r.name);
  if (roleNames.includes('ADMIN')) {
    menu.push({
      id: 1,
      label: 'Admin Panel',
      link: '/admin/users',
      icon: 'bx bx-grid-alt',
    });
  }
  if (
    roleNames.includes('SINGLE_USER')
  ) {
    const role = user.roles.find(x => x.name === 'SINGLE_USER');
    if (role) {
      menu.push({
        id: 1,
        label: 'Cliente Portal',
        link: '/products/portal',
        icon: 'bx bx-buildings',
      });
    }
  }
  if (
    roleNames.includes('WHOLESALE_USER')
  ) {
    const role = user.roles.find(x => x.name === 'WHOLESALE_USER');
    if (role) {
      menu.push({
        id: 1,
        label: 'Mayorista Portal',
        link: (() => {
          if (!user.emailConfirmedAt) {
            return '/account/auth/email-verification';
          } else if (user.wholesaleUsers && user.wholesaleUsers.isApproved) {
            return '/products/portal';
          } else {
            return '/wholesale-pending';
          }
        })(),
        icon: 'bx bx-buildings',
      });
    }
  }
  if(
    roleNames.includes('MARKETER_USER')
  ){
    const role = user.roles.find(x => x.name === 'MARKETER_USER');
    if (role) {
      menu.push({
        id: 1,
        label: 'Marketing Portal',
        link: '/admin/marketing/custom-popup',
        icon: 'bx bx-buildings',
      });
    }
  }
  if (
    roleNames.includes('SALE_USER')
  ) {
    menu.push({ 
      id: 1,
      label: 'Ventas',
      link: '/admin/orders',
      icon: 'bx bx-buildings',
    });
  }


  return menu;
}
