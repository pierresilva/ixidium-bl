import {AfterViewInit, Component, OnDestroy, OnInit, ViewChild, ViewEncapsulation} from '@angular/core';
import {Location} from '@angular/common';
import {Title} from '@angular/platform-browser';
import {ActivatedRoute, NavigationEnd, NavigationStart, Route, Router} from '@angular/router';
import {MzSidenavComponent, MzToastService} from '@ngx-materialize';
import {MalihuScrollbarService} from 'ngx-malihu-scrollbar';
import {LoadingHttpService} from './shared/services/loading-http.service';
import {AuthService} from './shared/services/auth.service';
import {Idle, DEFAULT_INTERRUPTSOURCES} from '@ng-idle/core';
import {Keepalive} from '@ng-idle/keepalive';
import 'rxjs/add/operator/filter';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/mergeMap';

declare var $: any;
import * as moment from 'moment';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';
import {environment} from '../environments/environment';

abstract class SectionRoutesPair {
  section: string;
  routes: Route[];
}

@Component({
  selector: 'renova-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class AppComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild('sidenav') sidenav: MzSidenavComponent;

  idleState = 'Not started.';
  timedOut = false;
  lastPing?: Date = null;
  currentRouteSubject = new BehaviorSubject<string>('');
  currentRoute = '';
  title = 'RECREAWEB';
  environment = environment;

  public constructor(private router: Router,
                     private idle: Idle,
                     private keepalive: Keepalive,
                     private toastService: MzToastService,
                     private auth: AuthService,
                     private mScrollbarService: MalihuScrollbarService,
                     private loading: LoadingHttpService,
                     private route: ActivatedRoute,
                     private titleService: Title,
                     private location: Location) {

    // set moment locale to es
    moment.locale('es');
    // sets an idle timeout of 5 seconds, for testing purposes.
    idle.setIdle(15);
    // sets a timeout period of 5 seconds. after 10 seconds of inactivity, the user will be considered timed out.
    idle.setTimeout(5);
    // sets the default interrupts, in this case, things like clicks, scrolls, touches to the document
    idle.setInterrupts(DEFAULT_INTERRUPTSOURCES);

    idle.onIdleEnd.subscribe(() => {
      this.idleState = 'No longer idle.';
      /*this.toastService.show(
          this.idleState,
          4000,
          'blue',
      );*/
    });

    idle.onTimeout.subscribe(() => {
      this.idleState = 'Timed out!';
      this.timedOut = true;
      this.reset();
      /*this.toastService.show(
      this.idleState,
          4000,
          'blue',
          () => {
              this.reset();
          }
      );*/
    });

    idle.onIdleStart.subscribe(() => {
      this.idleState = 'You\'ve gone idle!';
      /*this.toastService.show(
          this.idleState,
          4000,
          'blue'
      );*/
    });

    idle.onTimeoutWarning.subscribe((countdown) => {
      this.idleState = 'You will time out in ' + countdown + ' seconds!';
      /*this.toastService.show(
          this.idleState,
          4000,
          'blue',
      );*/
    });

    // sets the ping interval to 15 seconds
    keepalive.interval(15);

    keepalive.onPing.subscribe(() => this.lastPing = new Date());

    this.reset();
  }

  ngOnInit() {
    this.setNavigationStartEvent();
    this.auth.populateObservables();
    this.setNavigationEndEvent();
    this.currentRouteSubject.subscribe((data: any) => {
      this.currentRoute = data;
    });
  }

  ngAfterViewInit() {
  }

  ngOnDestroy() {
  }

  setNavigationStartEvent() {
    this.router.events
      .filter(event => event instanceof NavigationStart)
      .subscribe(() => {
        $('#main-preloader').removeClass('hidden');
        $('.main-content').addClass('hidden');
        this.loading.isLoadingSubject.next(true);
      });
  }

  setNavigationEndEvent() {
    // scroll to top on each route change
    this.router.events
      .filter(event => event instanceof NavigationEnd)
      .map(() => this.route)
      .map((route) => {
        while (route.firstChild) {
          route = route.firstChild;
        }
        return route;
      })
      .filter((route) => route.outlet === 'primary')
      .mergeMap((route) => route.data)
      .subscribe((event) => {
        window.scroll({
          top: 0,
          left: 0,
          behavior: 'smooth'
        });
        $('#main-preloader').addClass('hidden');
        $('.main-content').removeClass('hidden');
        this.loading.isLoadingSubject.next(false);
        this.titleService.setTitle(this.title + ' : ' + event['title']);
        this.currentRouteSubject.next(this.router.url);
      });
  }

  reset() {
    console.log('idle reset');
    this.idle.watch();
    this.idleState = 'Started.';
    this.timedOut = false;
  }

  backClick() {
    this.location.back();
  }

  backClickNon() {
    let urls = [
      '/home'
    ];

    for (let i = 0; i < urls.length; i++) {
      if (urls[i] === this.currentRoute) {
        return false;
      }
    }

    return true;
  }

}
