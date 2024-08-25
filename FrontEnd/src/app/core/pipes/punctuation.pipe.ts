import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'punctuation'
})
export class PunctuationPipe implements PipeTransform {

  transform(value: number | string | null | undefined): string {
    if (value == null) return ''; // Si el valor es null o undefined, devuelve una cadena vacía

    // Convierte el valor a string si es necesario para manipulación
    const stringValue = typeof value === 'number' ? value.toString() : value;

    // Separa la parte entera y la parte decimal (si existe)
    const parts = stringValue.split('.');
    let integerPart = parts[0]; // La parte entera

    // Agrega puntos como separadores de miles a la parte entera
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // Retorna el valor formateado con el prefijo de moneda
    return '$ ' + integerPart;
  }

}
