export interface CLodop {
  /** Crear una lista de impresoras */
  Create_Printer_List(el: Element): void;

  /** Crear una lista de tipos de papel */
  Create_PageSize_List(el: Element, iPrintIndex: number): void;

  /**
   * Determine si los atributos del protocolo https son compatibles.
   *
   * - 0 No soportado
   * - 1 Apoyo
   * - 2 Compatible e iniciado (el servicio https debe iniciarse por separado)
   */
  readonly HTTPS_STATUS: number;

  /** Función de devolución de llamada de resultado */
  On_Return(taskID: string, value: boolean | string): void;

  /** Función de devolución de llamada retenida */
  readonly On_Return_Remain: boolean;
}

export interface Lodop extends CLodop {
  [key: string]: any;

  /** Obtenga el número de versión del software */
  VERSION: string;

  /**
   * Inicialización de impresión. Inicialice el entorno de ejecución, limpie los recursos del sistema
   * impresos de forma anormal y configure el nombre del trabajo de impresión.
   *
   * **Recomendación o requisito:** La función y PRINT_INIT tienen función de inicialización, y cada
   * transacción de impresión se inicializa al menos una vez.
   * Se recomienda que el programa de impresión llame primero a la función.
   * El nombre de la tarea debe distinguirse de otras tareas de impresión tanto como sea posible,
   * por ejemplo, utilizando las palabras:
   * "XX Unit_XX Información de administración System_XX Subsystem_XX Module_XX Print Job".
   *
   * Cuando no desee que el usuario final cambie el diseño de impresión, configure strTaskName para que esté vacío.
   *
   * @param strTaskName Imprimir nombre del trabajo
   * @returns El retorno lógico indica que la inicialización se realizó correctamente y el falso lógico indica
   * que la inicialización falló. Los motivos de la falla son: no se completó la transacción de
   * impresión anterior, el sistema operativo no agregó una impresora (controlador), etc.
   */
  PRINT_INIT(strTaskName: string): boolean;

  /** Establecer el tamaño del papel. */
  SET_PRINT_PAGESIZE(
    intOrient: number,
    PageWidth: number | string,
    PageHeight: number | string,
    strPageName: string,
  ): void;

  /** Añadir elementos de impresión de hipertexto (modo normal) */
  ADD_PRINT_HTM(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strHtmlContent: string,
  ): void;

  /** Agregar un elemento de impresión de tabla (modo de hipertexto) */
  ADD_PRINT_TABLE(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strHtml: string,
  ): void;

  /** Agregar un elemento de impresión de tabla (modo de hipertexto)*/
  ADD_PRINT_TABLE(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strHtml: string,
  ): void;

  /** Añadir elementos de impresión de hipertexto (modo URL)*/
  ADD_PRINT_URL(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strURL: string,
  ): void;

  /** Añadir elementos de impresión de texto sin formato */
  ADD_PRINT_TEXT(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strContent: string,
  ): void;

  /** Aumentar los elementos de impresión de imagen */
  ADD_PRINT_IMAGE(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strHtmlContent: string,
  ): void;

  /** Agrega un rectángulo */
  ADD_PRINT_RECT(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    intLineStyle: number,
    intLineWidth: number,
  ): void;

  /** Aumentar la línea elíptica. */
  ADD_PRINT_ELLIPSE(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    intLineStyle: number,
    intLineWidth: number,
  ): void;

  /** Aumentar linea recta */
  ADD_PRINT_LINE(
    Top1: number | string,
    Left1: number | string,
    Top2: number | string,
    Left2: number | string,
    intLineStyle: number,
    intLineWidth: number,
  ): void;

  /** Añadir código de barras */
  ADD_PRINT_BARCODE(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    CodeType: string,
    CodeValue: string,
  ): void;

  /** Aumentar tabla */
  ADD_PRINT_CHART(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    ChartType: number,
    strHtml: string,
  ): void;

  /** Cargando plantilla de documento */
  ADD_PRINT_DATA(strDataStyle: string, varDataValue: any): void;

  /** Establecer el estilo del elemento de impresión */
  SET_PRINT_STYLE(strStyleName: LodopStyleValue, varStyleValue: number | string): void;

  /** Vista previa de impresión */
  PREVIEW(): number;

  /** Impresión directa */
  PRINT(): string;

  /** Mantenimiento de impresión */
  PRINT_SETUP(): string;

  /** Diseño de impresión */
  PRINT_DESIGN(): string;

  /** Paginación forzada */
  NEWPAGE(): boolean;

  /** Obtenga la cantidad de dispositivos de impresión */
  GET_PRINTER_COUNT(): number;

  /** Obtenga el nombre del dispositivo de impresión */
  GET_PRINTER_NAME(strPrinterIDandType: number | string): string;

  /** Dispositivo de impresión designado */
  SET_PRINTER_INDEX(oIndexOrName: number | string): boolean;
  /** [CLodop] Especifique la impresora */
  SET_PRINTER_INDEX(
    DriverIndex: number | string,
    PrinterIDandName: number | string,
    SubDevIndex: number | string,
  ): boolean;

  /** Seleccione dispositivo de impresión */
  SELECT_PRINTER(): number;

  /** Establecer el modo de visualización */
  SET_SHOW_MODE(strModeType: string, varModeValue: number | string): boolean;

  /** Establecer el modo de impresión */
  SET_PRINT_MODE(strModeType: string, varModeValue: number | string): boolean | string;

  /** Establecer el número de copias. */
  SET_PRINT_COPIES(intCopies: number): boolean;

  /** Configurar ventana de vista previa */
  SET_PREVIEW_WINDOW(
    intDispMode: number,
    intToolMode: number,
    blDirectPrint: number,
    inWidth: number,
    intHeight: number,
    strTitleButtonCaptoin: string,
  ): void;

  /** Especificar imagen de fondo */
  ADD_PRINT_SETUP_BKIMG(strImgHtml: string): void;

  /** Enviar datos en bruto */
  SEND_PRINT_RAWDATA(strRawData: string): boolean;

  /** Escribir datos de puerto */
  WRITE_PORT_DATA(strPortName: string, strData: string): boolean;

  /** Leer datos de puerto */
  READ_PORT_DATA(strPortName: string): string;

  /** Obtener el nombre del perfil */
  GET_PRINT_INIFFNAME(strPrintTask: string): string;

  /** Obtener una lista de nombres de tipo de papel */
  GET_PAGESIZES_LIST(oPrinterName: number | string, strSplit: string): string;

  /** Escribir contenido de archivo local */
  WRITE_FILE_TEXT(intWriteMode: number | string, strFileName: string, strText: string): string;

  /** Leer el contenido del archivo local */
  GET_FILE_TEXT(strFileName: string): string | null;

  /** Leer la hora local del archivo */
  GET_FILE_TIME(strFileName: string): string | null;

  /** Determine si existe un archivo local */
  IS_FILE_EXIST(strFileName: string): boolean;

  /** Obtener información del sistema */
  GET_SYSTEM_INFO(strInfoType: string): boolean;

  /** Obtener valor de datos */
  GET_VALUE(ValueType: string, ValueIndex: number | string): any;

  /** Conversión de formato de datos */
  FORMAT(oType: string, oValue: any): any;

  /** Obtener el valor del resultado del diálogo */
  GET_DIALOG_VALUE(oType: string, oPreValue: string): string;

  /** (mejorado) inicialización de impresión */
  PRINT_INITA(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strPrintName: string,
  ): boolean;

  /** (Mejorado) Aumente los elementos de impresión de hipertexto (Modo de gráficos) */
  ADD_PRINT_HTML(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strHtmlContent: string,
  ): void;

  /** (Mejorado) Aumentar el elemento de impresión de formulario (modo URL) */
  ADD_PRINT_TBURL(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strURL: string,
  ): void;

  /** (mejorado) aumentar elementos de impresión de texto sin formato */
  ADD_PRINT_TEXTA(
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    strContent: string,
  ): void;

  /** (Mejorado) Establece el estilo A del elemento de impresión, hereda todos los atributos de `SET_PRINT_STYLE` */
  SET_PRINT_STYLEA(
    varItemNameID: number | string,
    strStyleName: string,
    varStyleValue: number | string,
  ): void;

  /** (mejorado) exportar datos a un archivo*/
  SAVE_TO_FILE(strFileName: string): boolean;

  /** Modo de guardado de configuración (mejorado)*/
  SET_SAVE_MODE(varModeName: string, varModeValue: number | string): boolean;

  /** (mejorado) aumentar gráficos */
  ADD_PRINT_SHAPE(
    intShapeType: number,
    Top: number | string,
    Left: number | string,
    Width: number | string,
    Height: number | string,
    intLineStyle: number,
    intLineWidth: number,
    varColor: number | string,
  ): void;

  /** Dispositivo de impresión designado (mejorado) */
  SET_PRINTER_INDEXA(oIndexOrName: number | string): boolean;

  /** (mejorado) paginación forzada*/
  NEWPAGEA(): boolean;

  /** (Mejorado) Vista previa de impresión A*/
  PREVIEWA(): number;

  /** (mejorado) vista previa de impresión B */
  PREVIEWB(): number;

  /** Impresión directa A */
  PRINTA(): boolean;

  /** Impresión directa B */
  PRINTB(): boolean;

  /** Mostrar gráfico */
  SHOW_CHART(): void;

  /** Acción de control de interfaz */
  DO_ACTION(ActName: string, ActValue: number | string): void;

  /**
   * Establecer información de registro del producto de software
   *
   * @param  strCompanyName El nombre de la unidad de registro, el propósito es el mismo que el parámetro de control CompanyName.
   * @param  strLicense El número de registro principal es el mismo que el de la licencia de parámetros de control.
   * @param  strLicenseA Número de registro adicional A, el mismo que el parámetro de control LicenseA.
   * @param  strLicenseB Número de registro adicional B, el mismo que el parámetro de control LicenseB.
   */
  SET_LICENSES(
    strCompanyName: string,
    strLicense: string,
    strLicenseA?: string,
    strLicenseB?: string,
  ): void;

  webskt: WebSocket;
}

export type LodopStyleValue =
  | 'FontName'
  | 'FontSize'
  | 'FontColor'
  | 'Bold'
  | 'Italic'
  | 'Underline'
  | 'Alignment'
  | 'Angle'
  | 'ItemType'
  | 'HOrient'
  | 'VOrient'
  | 'PenWidth'
  | 'PenStyle'
  | 'Stretch'
  | 'PreviewOnly'
  | 'ReadOnly';

export interface LodopResult {
  /** éxito */
  ok: boolean;
  /** Código de error */
  status?: string;
  /** Objeto LODOP cuando tenga éxito */
  lodop?: Lodop;
  /** Mensaje de error */
  error?: any;
}

export interface LodopPrintResult {
  /** éxito */
  ok: boolean;
  /** Código de error */
  error?: string;
  /** Código */
  code: string;
  /** Objeto de contexto de parámetro dinámico */
  item: any;
  /** Expresión regular para análisis de código */
  parser?: RegExp;
}
