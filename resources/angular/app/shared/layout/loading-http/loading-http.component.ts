import {Component, OnInit, ViewEncapsulation} from '@angular/core';
import {LoadingHttpService} from '../../services/loading-http.service';

@Component({
  selector: 'renova-loading-http',
  templateUrl: './loading-http.component.html',
  styleUrls: ['./loading-http.component.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class LoadingHttpComponent implements OnInit {

  isLoading = false;
  constructor(
    private loading: LoadingHttpService
  ) {
  }

  ngOnInit() {
    this.loading.isLoadingSubject.subscribe((data: any) => {
      this.isLoading = data;
    });

  }

}
