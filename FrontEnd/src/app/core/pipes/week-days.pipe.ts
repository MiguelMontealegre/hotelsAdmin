import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'dayName'
})
export class DayNamePipe implements PipeTransform {
  transform(dayNumber: string): string {
    switch (dayNumber) {
      case '1':
        return 'Monday';
      case '2':
        return 'Tuesday';
      case '3':
        return 'Wednesday';
      case '4':
        return 'Thursday';
      case '5':
        return 'Friday';
      case '6':
        return 'Saturday';
      case '7':
        return 'Sunday';
      default:
        return '';
    }
  }
}
