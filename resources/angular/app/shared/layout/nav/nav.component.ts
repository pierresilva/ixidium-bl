import {Component, OnInit, ViewEncapsulation} from '@angular/core';
import {AuthService} from '../../services/auth.service';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';

@Component({
  selector: 'renova-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class NavComponent implements OnInit {

  userSubject = new BehaviorSubject({});
  user: any;

  constructor(private auth: AuthService) { }

  ngOnInit() {
    this.user = {};
    this.userSubject.next(this.auth.getUser());
    this.userSubject.subscribe((data) => {
      this.user = data;
    });
  }

}
