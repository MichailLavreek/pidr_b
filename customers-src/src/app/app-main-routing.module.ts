import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CustomersComponent} from "./customers/customers.component";
import {AddCustomerComponent} from "./customers/add-customer/add-customer.component";
import {EditCustomerComponent} from "./customers/edit-customer/edit-customer.component";

/*
    Експорт настроек роутинга для управления доступами
*/
export const appRoutes: Routes = [
    {
        path: '',
        pathMatch: 'full',
        component: CustomersComponent,
    },
    {
        path: 'add-customer',
        component: AddCustomerComponent,
    },
    {
        path: 'edit-customer',
        component: EditCustomerComponent,
    }
];
@NgModule({
    imports: [
        RouterModule.forRoot(appRoutes)
    ],
    exports: [
        RouterModule
    ]
})
export class AppMainRoutingModule {
}
