import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
    providedIn: 'root'
})
export class CustomersService {

    constructor(private http: HttpClient) {

    }

    public getCustomers() {
        return new Promise(((resolve, reject) => {
            this.http.get('/cms/ca-api/customers', {headers: {XMLHttpRequest: 'true'}})
                .subscribe((data) => {
                    resolve(data);
                }, (error) => {
                    window.location.href = `/cms/login?_target_path=${window.location.href}index.html`
                });
        }));
    }

    public getCustomer(id) {
        return new Promise(((resolve, reject) => {
            this.http.get(`/cms/ca-api/customer/${id}`, {headers: {XMLHttpRequest: 'true'}})
                .subscribe((data) => {
                    resolve(data);
                }, (error) => {
                    window.location.href = `/cms/login?_target_path=${window.location.href}index.html`
                });
        }));
    }

    public deleteCustomer(id) {
        return new Promise(((resolve, reject) => {
            this.http.delete(`/cms/ca-api/customer/${id}`, {headers: {XMLHttpRequest: 'true'}})
                .subscribe((data) => {
                    resolve(data);
                }, (error) => {
                    window.location.href = `/cms/login?_target_path=${window.location.href}index.html`
                });
        }));
    }

    public setCustomer(customer) {
        return new Promise(((resolve, reject) => {
            this.http.post('/cms/ca-api/customers', {customer: customer})
                .subscribe((data) => {
                    resolve(data);
                }, (error) => {
                    reject(error);
                });
        }));
    }
}
