/**
 * Simple string helpers
 */
import {weekdays} from 'moment';

export class HelperString {
  /**
   * Returns a slusify string
   *
   * @param str
   * @returns {any}
   */
  public slugify(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    const from = 'àáäâèéëêìíïîòóöôùúüûñç·/_,:;';
    const to = 'aaaaeeeeiiiioooouuuunc------';
    for (let i = 0, l = from.length; i < l; i++) {
      str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
      .replace(/\s+/g, '-') // collapse whitespace and replace by -
      .replace(/-+/g, '-'); // collapse dashes

    return str;
  }

  getNameDay(code) {
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    return days[code];
  }

  getArrayDay() {
    const days = [
      {id: '0', name: 'Domingo'},
      {id: '1', name: 'Lunes'},
      {id: '2', name: 'Martes'},
      {id: '3', name: 'Miércoles'},
      {id: '4', name: 'Jueves'},
      {id: '5', name: 'Viernes'},
      {id: '6', name: 'Sábado'}
    ];
    return days;
  }

  public getNameDayMassiveByString(codes, separator) {
    const arrCodes = String(codes).split(separator);
    const arrDays = [];

    for (let i = 0; i < arrCodes.length; i++) {
      const codeDay = arrCodes[i];
      arrDays.push(this.getNameDay(codeDay));
    }

    return arrDays;
  }

  /**
   * Función que exporta el contenido HTML a un archivo de excel
   * @returns {Window | null}
   */
  downloadExcel(title, tableID) {

    const date = new Date();
    let dateFormat = date.getDate().toString() + '/' + (date.getMonth() + 1).toString() + '/' + date.getFullYear().toString() + ' ';
    dateFormat += date.getHours().toString() + ':' + date.getMinutes().toString() + ':' + date.getSeconds().toString();

    let htmlHeader = '<h1>' + title + '</h1>';
    htmlHeader += '<h3> Fecha de generación : ' + dateFormat + '</h3>';
    let html = htmlHeader + $('#' + tableID).closest('div').html();
    html = this.getFormatHtml(html);

    const sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));

    return (sa);
  }

  /**
   * Función que se encargará de convertir las tildes en formato HTML
   * @param html
   * @returns {any}
   */
  getFormatHtml(html) {
    html = String(html);

    // Modificar tildes
    html = html.split('á').join('&aacute;');
    html = html.split('é').join('&eacute;');
    html = html.split('í').join('&iacute;');
    html = html.split('ó').join('&oacute;');
    html = html.split('ú').join('&uacute;');
    html = html.split('Á').join('&Aacute;');
    html = html.split('É').join('&Eacute;');
    html = html.split('Í').join('&Iacute;');
    html = html.split('Ó').join('&Oacute;');
    html = html.split('Ú').join('&Uacute;');
    html = html.split('Ñ').join('&Ntilde;');
    html = html.split('ñ').join('&ntilde;');
    return html;
  }

}
