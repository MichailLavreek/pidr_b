import {AfterViewInit, Component, Input, OnDestroy, OnInit} from '@angular/core';
import {Subscription} from 'rxjs';

/**
 * Компонент центральной области приложения. Содержит компоненты Content и Sidebar.
 */
@Component({
    selector: 'app-central-area',
    templateUrl: './central-area.component.html',
    styleUrls: ['./central-area.component.scss']
})
export class CentralAreaComponent implements OnInit, OnDestroy{
    public openCloseState = false;
    private sub: Subscription;

    constructor() {
    }


    ngOnInit(): void {
    }

    ngOnDestroy(): void {
        this.sub.unsubscribe();
    }
}
