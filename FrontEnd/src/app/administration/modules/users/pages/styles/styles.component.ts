import { Component, OnInit } from '@angular/core';
import { AdminStyleService } from '@services/admin-style.service';
import { CommonApiService } from '@services/common/common-api.service';
import { CommonVerbsApiService } from '@services/common/common-verbs-api.service';
import { ModelService } from '@services/common/model.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-styles',
  templateUrl: './styles.component.html',
  styleUrls: ['./styles.component.scss'],
  providers: [
    { provide: 'API_SERVICE', useValue: 'styles' },
    CommonApiService,
    CommonVerbsApiService,
    ModelService,
  ],
})
export class StylesComponent implements OnInit {

  constructor(
    private adminStyleService: AdminStyleService,
    public api: CommonApiService,
    public toast: ToastrService

  ) { }

  primaryColor: string;
  secondaryColor: string;
  successColor: string;
  infoColor: string;
  warningColor: string;
  dangerColor: string;
  lightColor: string;
  darkColor:string;
  textColor:string;
  bodyColor:string;
  bodyBgColor:string;

  //vars Header
  headingColor: string;
topbarSearchColor: string;
topnavColor: string;
headerItemColor: string;
boxedBodyColor: string;
inputFocusBorderColor: string;
menuItemColor: string;
headingDarkBgColor: string;

//variables Footer
footerInputBorderColor: string;
footerBgColor: string;
footerColor: string;
customWhiteColor: string;
hrBorderColor: string;


ngOnInit(): void {
  const colorVariables = [
    'bs-primary-rgb',
    'bs-secondary-rgb',
    'bs-success-rgb',
    'bs-info-rgb',
    'bs-warning-rgb',
    'bs-danger-rgb',
    'bs-light-rgb',
    'bs-dark-rgb',
    'bs-white-rgb'
  ];

  this.headingColor = this.adminStyleService.getVariable('bs-heading-bg');
  this.topbarSearchColor = this.adminStyleService.getVariable('bs-topbar-search-bg');
  this.topnavColor = this.adminStyleService.getVariable('bs-topnav-bg');
  this.headerItemColor = this.adminStyleService.getVariable('bs-header-item-color');
  this.boxedBodyColor = this.adminStyleService.getVariable('bs-boxed-body-bg');
  this.inputFocusBorderColor = this.adminStyleService.getVariable('bs-input-focus-border-color');
  this.menuItemColor = this.adminStyleService.getVariable('bs-menu-item-color');
  this.headingDarkBgColor = this.adminStyleService.getVariable('bs-heading-dark-bg');
  this.footerInputBorderColor = this.adminStyleService.getVariable('bs-input-border-color');
  this.footerBgColor = this.adminStyleService.getVariable('bs-footer-bg');
  this.footerColor = this.adminStyleService.getVariable('bs-footer-color');
  this.customWhiteColor = this.adminStyleService.getVariable('bs-custom-white');
  this.hrBorderColor = this.adminStyleService.getVariable('bs-hr-border-color');
  

  colorVariables.forEach(variable => {
    let color = this.adminStyleService.getVariable(variable);
    color = this.rgbToHex(color);
    this[`${variable.split('-')[1]}Color`] = color;
  });
this.bodyBgColor= this.adminStyleService.getVariable('bs-body-bg');
  this.bodyColor = this.adminStyleService.getVariable('bs-body-color');
}

  changeRGBcolor(variableName: string, colorHex: string,keycolor=null) {
    if (!/^#([0-9A-F]{3}){1,2}$/i.test(colorHex)) {
      return;
    }
    let r = parseInt(colorHex.substring(1, 3), 16);
    let g = parseInt(colorHex.substring(3, 5), 16);
    let b = parseInt(colorHex.substring(5, 7), 16);
    let rgbColor = `${r}, ${g}, ${b}`;
    this.adminStyleService.setVariable(variableName, rgbColor);
    if(keycolor){
      this.changeClass(keycolor,colorHex);
    }
  }

  changeTextColor(colorHex: string) {
    if (!/^#([0-9A-F]{3}){1,2}$/i.test(colorHex)) {
      return;
    }
    let r = parseInt(colorHex.substring(1, 3), 16);
    let g = parseInt(colorHex.substring(3, 5), 16);
    let b = parseInt(colorHex.substring(5, 7), 16);
    let rgbColor = `${r}, ${g}, ${b}`;
    this.adminStyleService.setVariable('bs-heading-color', rgbColor);
    this.adminStyleService.setVariable('bs-white-rgb', rgbColor);

  }
  changeColorBodyText(color: string) {
    this.adminStyleService.setVariable('bs-body-color', color);
  }
  changeColorVariable(variableName: string, color: string) {
    this.adminStyleService.setVariable(variableName, color);
  }
  resetColors() {
    this.adminStyleService.resetVariablesInLocalStorage();
  }

  changeClass(className: string, primaryColor: string) {
    const calcularEstilos = (colorPrincipal: string) => {
      const hoverBg = colorPrincipal === '#fff' ? '#f8f9fa' : this.lightenDarkenColor(colorPrincipal, 20);
      const hoverBorderColor = colorPrincipal === '#fff' ? '#f8f9fa' : this.lightenDarkenColor(colorPrincipal, 15);
      const activeBg = colorPrincipal === '#fff' ? '#f8f9fa' : this.lightenDarkenColor(colorPrincipal, 15);
      const activeBorderColor = colorPrincipal === '#fff' ? '#f8f9fa' : this.lightenDarkenColor(colorPrincipal, 10);
      const activeShadow = `inset 0 3px 5px rgba(0, 0, 0, 0.125)`;
      const disabledBg = colorPrincipal === '#fff' ? '#f8f9fa' : this.darkenColor(colorPrincipal, 15);
      const disabledBorderColor = colorPrincipal === '#fff' ? '#f8f9fa' : this.darkenColor(colorPrincipal, 15);
  
      return {
        [  className + '-btn-color']: '#fff',
        [  className + '-btn-bg']: colorPrincipal,
        [  className + '-btn-border-color']: colorPrincipal,
        [className + '-btn-hover-color']: '#fff',
        [ className + '-btn-hover-bg']: hoverBg,
        [ className + '-btn-hover-border-color']: hoverBorderColor,
        [ className + '-btn-focus-shadow-rgb']: '111, 132, 234',
        [ className + '-btn-active-color']: '#fff',
        [ className + '-btn-active-bg']: activeBg,
        [ className + '-btn-active-border-color']: activeBorderColor,
        [ className + '-btn-active-shadow']: activeShadow,
        [ className + '-btn-disabled-color']: '#fff',
        [ className + '-btn-disabled-bg']: disabledBg,
        [ className + '-btn-disabled-border-color']: disabledBorderColor
      };
      
    };
  
    const styles = calcularEstilos(primaryColor);
  for (const [variableName, color] of Object.entries(styles)) {
    this.adminStyleService.setVariable(variableName, color);
  }
  }
  
  lightenDarkenColor(col: string, amt: number): string {
    let usePound = false;
    if (col[0] === "#") {
      col = col.slice(1);
      usePound = true;
    }

    let num = parseInt(col, 16);
    let r = (num >> 16) + amt;
    if (r > 255) r = 255;
    else if (r < 0) r = 0;

    let b = ((num >> 8) & 0x00ff) + amt;
    if (b > 255) b = 255;
    else if (b < 0) b = 0;

    let g = (num & 0x0000ff) + amt;
    if (g > 255) g = 255;
    else if (g < 0) g = 0;

    return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);
  }

  darkenColor(col: string, amt: number): string {
    return this.lightenDarkenColor(col, -amt);
  }

  rgbToHex(rgb: string): string {
    // Dividir la cadena en componentes RGB
    const rgbValues = rgb.split(',').map(value => parseInt(value.trim()));

    // Convierte los valores a hexadecimal y concaténalos
    const r = rgbValues[0].toString(16).padStart(2, '0');
    const g = rgbValues[1].toString(16).padStart(2, '0');
    const b = rgbValues[2].toString(16).padStart(2, '0');

    return `#${r}${g}${b}`;
}

saveStyles() {
  const styles = this.adminStyleService.getVariablesFromLocalStorage();
  const stylesArray ={
    styles :  Object.entries(styles).map(([key, value]) => ({ key, value })),
  }

  this.api.post('', stylesArray).subscribe(
    (response) => {
      this.toast.success('Estilos guardados correctamente');
    },
    (error) => {
      this.toast.error('Error al guardar los estilos');
    }
  );
}




  // Ejemplo de uso 




}
