export class HelperInvoice {

  private localeString = null;

  constructor() {
    this.localeString = 'de-DE';
  }

  openPopupCashReturn(params = {total_method_payment: 0, total_product: 0, url: null}) {

    // Obtenemos el cambio
    const cashReturn = this.getCashReturn(params.total_method_payment, params.total_product);

    // definimos la anchura y altura de la ventana
    const height = 180;
    const width = 300;

    // calculamos la posicion x e y para centrar la ventana
    const y = Number((window.innerHeight / 2) - (height / 2));
    const x = Number((window.innerWidth / 2) - (width / 2));

    // mostramos la ventana centrada
    // tslint:disable-next-line:max-line-length
    const popup = window.open('', 'CAMBIO', 'width=' + width + ',height=' + height + ',top=' + y + ',left=' + x + ',toolbar=no,resizable=no');

    popup.document.body.innerHTML = `<div style="text-align: center; font-family: BlinkMacSystemFont, sans-serif;">
      <fieldset>
      <legend>CAMBIO</legend>
      <span style="color: green; font-weight: bold; font-size: 40px;"> $ ${this.setThousandsSeparator(cashReturn)} </span>
      </fieldset>
      <a style="text-decoration: none;display: inline-block;height: 36px;line-height: 36px;padding: 0 2rem;text-transform: uppercase;
      vertical-align: middle;background: #F44336 !important;color: white;margin-top: 20px;"
      href="javascript:window.open('','_self').close();">CERRAR</a>
      </div>
        `;

    // Cuando se presione Enter
    popup.addEventListener('keyup', function (event) {
      event.preventDefault();
      if (event.key === 'Enter') {
        // Cierro la ventana
        this.close();
      }
    });

    // Cuando se cierre la ventana
    popup.onbeforeunload = function () {
      if (params.url) {
        window.open(params.url, '_blank');
      }
    };

  }

  setThousandsSeparator(value) {
    return Number(value).toLocaleString(this.localeString);
  }

  getCashReturn(total_method_payment, total_product) {
    let changeReturn = 0;
    changeReturn = Number(total_method_payment) - Number(total_product);

    if (changeReturn < 0) {
      changeReturn = 0;
    }

    return changeReturn;
  }

}
