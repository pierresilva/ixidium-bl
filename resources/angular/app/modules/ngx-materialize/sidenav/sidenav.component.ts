import {AfterViewInit, Component, Input, OnDestroy, ElementRef, Renderer, ViewChild} from '@angular/core';

declare var $: any;
declare var M: any;

@Component({
  selector: 'mz-sidenav',
  templateUrl: './sidenav.component.html',
})
export class MzSidenavComponent implements AfterViewInit, OnDestroy {
  @Input() backgroundClass: string;
  @Input() closeOnClick: boolean;
  @Input() collapseButtonId: string;
  @Input() draggable: boolean;
  @Input() edge: string;
  @Input() fixed: boolean;
  @Input() id: string;
  @Input() onClose: Function;
  @Input() onOpen: Function;
  @Input() width: number;

  @ViewChild('sidenav') sidenav: ElementRef;

  private _opened = false;
  private collapseButton: any;

  constructor(public renderer: Renderer) {
  }

  get opened() {
    return this._opened;
  }

  set opened(value: boolean) {
    this._opened = value;
    this.collapseButton.sidenav(this._opened ? 'close' : 'open');
  }

  ngAfterViewInit() {
    this.initSidenav();
    this.initCollapseButton();
    this.initCollapsibleLinks();
  }

  ngOnDestroy() {
    // this.collapseButton.sidenav('destroy');
  }

  initSidenav() {
    // this.renderer.invokeElementMethod($(this.sidenav.nativeElement), 'sidenav', );
    if (this.closeOnClick) {
      $('.sidenav li.sidenav-link a').click(() => {
        $(this.sidenav.nativeElement).sidenav('close');
      });
    }
    $(this.sidenav.nativeElement).sidenav({
      draggable: this.draggable != null ? this.draggable : true,
      edge: this.edge || 'left',
      menuWidth: isNaN(this.width) ? 300 : this.width,
      onCloseEnd: this.onClose,
      onOpenEnd: this.onOpen,
    });
  }

  initCollapseButton() {
    // fake button if no collapseButtonId is provided
    this.collapseButton = this.collapseButtonId
      ? $(`#${this.collapseButtonId}`)
      : $(document.createElement('template'));

    // add data-activates to collapse button
    this.collapseButton.attr('data-target', this.id);

    // extend onOpen function to update opened state
    const onOpen = this.onOpen || (() => {
    });
    this.onOpen = () => {
      onOpen();
      this._opened = true;
    };

    // extend onClose function to update opened state
    const onClose = this.onClose || (() => {
    });
    this.onClose = () => {
      onClose();
      this._opened = false;
    };

    // initialize sidenav
    /*this.collapseButton.sidenav({
      closeOnClick: this.closeOnClick || false,
      draggable: this.draggable != null ? this.draggable : true,
      edge: this.edge || 'left',
      menuWidth: isNaN(this.width) ? 300 : this.width,
      onClose: this.onClose,
      onOpen: this.onOpen,
    });*/
  }

  initCollapsibleLinks() {
    // initialize collapsible elements
    $(`#${this.id} .collapsible`).collapsible();
  }
}
