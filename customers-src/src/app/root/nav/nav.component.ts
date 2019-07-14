import {
    AfterViewInit,
    ChangeDetectorRef,
    Component,
    ElementRef,
    EventEmitter,
    HostListener,
    Input,
    OnDestroy,
    OnInit,
    Output
} from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import {Subscription} from 'rxjs';

// import {GoogleUserService} from '../../authorization/gapi/UserService';

/**
 * Компонент, содержащий хедер и навигацию приложения.
 */
@Component({
    selector: 'app-nav',
    templateUrl: './nav.component.html',
    styleUrls: ['./nav.component.scss'],
})
export class NavComponent implements OnInit, AfterViewInit, OnDestroy {

    public innerWidth;

    // "name" key is deprecated because translations
    public menu: any = [
    ];

    private loggedInType: string;
    public permissions: any;
    public showProfile = false;

    public showNavSubmenu = false;
    public showMobInfoBar = true;

    // @deprecated use "user$" instead
    public user;

    public user$;
    public activeUsers: any = [];

    public buttonStates = {submenu: false, nafigation: false, info: false};
    public buttonStatesSubscription: Subscription[] = [];

    constructor(
        private elementRef: ElementRef,
        private changeDetectorRef: ChangeDetectorRef,
    ) {
    }

    ngOnInit() {
        this.setupAdaptiveComponentsRelation();

        this.innerWidth = window.innerWidth;
    }

    ngOnDestroy(): void {
        this.buttonStatesSubscription.forEach((sub) => {
            sub.unsubscribe();
        });
    }

    @HostListener('window:resize', ['$event'])
    onResize(event) {
        this.innerWidth = window.innerWidth;
    }

    private setupAdaptiveComponentsRelation() {
    }

    public isAllowed(resource) {
        if (!this.permissions) {
            return false;
        }

        if (typeof resource === 'string') {
            return !!this.permissions.find((item) => item.resource === resource && item.allowed);
        }

        if (Array.isArray(resource)) {
            return !!this.permissions.find((item) => resource.includes(item.resource) && item.allowed);
        }
    }

    @HostListener('document:click', ['$event'])
    private onClick(event) {
        if (!this.elementRef.nativeElement.contains(event.target)) {
            this.closeSubmenus();
        }
    }

    /**
     * Выход пользователя.
     */
    public logout() {

        switch (this.loggedInType) {
            case 'app':
                this.logoutApp();
                break;
            // case 'google':
            //     this.logoutGoogle();
            //     break;
        }

    }

    private logoutApp() {
    }

    // private logoutGoogle() {
    //     this.userService.signOut();
    // }

    /**
     * Проверка - авторизован ли пользователь.
     *
     * @returns {boolean}
     */
    public isLoggedIn() {
    }


    ngAfterViewInit() {

    }

    public closeSubmenus() {
        this.menu.forEach((item) => item.active = false);
        // this.selectedLanguage.opened = false;
        this.showProfile = false;
        // this.showLang = false;
    }

    public menuLinkClicked(menuLink: any) {
        this.closeSubmenus();
        this.menu.forEach((item) => item.highlight = false);
        menuLink.active = !menuLink.active;
    }

    public toggleProfile() {
        this.showProfile = !this.showProfile;
    }

    public toggleNavSubmenu() {
        this.showNavSubmenu = !this.showNavSubmenu;
    }

    public toggleMobInfoBar() {
        this.showNavSubmenu = !this.showNavSubmenu;
    }

    public childLinkClicked(parent) {
        this.menu.forEach((item) => item.highlight = false);
        parent.highlight = true;
    }
}

