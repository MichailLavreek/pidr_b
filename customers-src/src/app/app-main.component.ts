import {Component, enableProdMode, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {environment} from '../environments/environment';

/**
 * Корневой компонент приложения.
 */
@Component({
    selector: 'app-main',
    templateUrl: './app-main.component.html',
    styleUrls: ['./app-main.component.scss'],
})
export class AppMainComponent implements OnInit, OnDestroy {
    private eventListener;

    constructor(private router: Router) {

        console.log('env-prod: ', environment.production);

    }

    ngOnInit(): void {
    }

    ngOnDestroy(): void {
    }
}
