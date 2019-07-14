import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClient, HttpClientModule} from '@angular/common/http';
import {AppMainComponent} from './app-main.component';
import {AppMainRoutingModule} from './app-main-routing.module';
import {CookieService} from 'angular2-cookie/core';
import {HttpModule} from '@angular/http';
import {
    MatButtonModule,
    MatCardModule, MatCheckboxModule, MatDatepickerModule, MatDialogModule, MatFormFieldModule,
    MatIconModule, MatInputModule,
    MatListModule, MatNativeDateModule, MatPaginatorModule,
    MatSidenavModule, MatSortModule, MatTableModule,
    MatToolbarModule
} from '@angular/material';
import {JcfModule} from 'angular2-jcf-directive';
import {BlockUIModule} from 'ng-block-ui';
import {TranslateHttpLoader} from '@ngx-translate/http-loader';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {CentralAreaComponent} from "./root/central-area/central-area.component";
import {ContentComponent} from "./root/content/content.component";
import {NavComponent} from "./root/nav/nav.component";
import {CustomersService} from "./customers/customers.service";
import {CustomersComponent} from "./customers/customers.component";
import { AddCustomerComponent } from './customers/add-customer/add-customer.component';
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";
import {EditCustomerComponent} from './customers/edit-customer/edit-customer.component';
import { DialogContentComponent } from './customers/dialog-content/dialog-content.component';

@NgModule({
    declarations: [
        AppMainComponent,
        CentralAreaComponent,
        ContentComponent,
        CustomersComponent,
        NavComponent,
        AddCustomerComponent,
        EditCustomerComponent,
        DialogContentComponent,
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        FormsModule,
        MatInputModule,
        MatTableModule,
        MatPaginatorModule,
        MatDialogModule,
        MatSortModule,
        MatCheckboxModule,
        ReactiveFormsModule,
        MatFormFieldModule,
        MatDatepickerModule,
        MatNativeDateModule,
        HttpClientModule,
        HttpModule,
        MatToolbarModule,
        MatButtonModule,
        MatSidenavModule,
        MatIconModule,
        MatListModule,
        MatCardModule,
        JcfModule,
        AppMainRoutingModule,
        BlockUIModule.forRoot(),
        NgbModule
    ],
    providers: [
        {provide: CookieService, useFactory: cookieServiceFactory},
        CustomersService
    ],
    bootstrap: [AppMainComponent],
    entryComponents: [
        DialogContentComponent
    ]
})
export class AppMainModule {}
/* Фикс ошибки, которую выдает модуль angular-cookie при компиляции */
export function cookieServiceFactory() { return new CookieService(); }

// required for AOT compilation
export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http);
}
