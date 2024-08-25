import { MenuItem } from '@models/layout/menu.model';

export const dataModules: MenuItem[] = [
  {
    id: 3,
    label: 'Hotels',
    icon: 'bx-user-circle',
    link: '/hotels/portal',
  },
  {
    id: 3,
    label: 'Suites',
    icon: 'bx-user-circle',
    link: '/products/portal',
  },
  {
    id: 3,
    label: 'Reservas',
    icon: 'bx-receipt',
    link: '/products/orders',
  },
  {
    id: 4,
    label: 'Suites Favoritas',
    icon: 'bx-heart',
    link: '/products/favorites',
  },

];
